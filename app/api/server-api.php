<?php

function mcd_rest_list_accounts(WP_REST_Request $request) {
    $whm_user = 'builderlab';
    $whm_token = '3V4W9CVOVK4LM10C8GTZJBVRJIQJ9ZMA';
    $whm_host = 'https://165.227.221.35:2087';

    $query = "https://165-227-221-35.cprapid.com:2087/json-api/applist?api.version=1";
    
    // $query = "https://165-227-221-35.cprapid.com:2087/json-api/convert_addon_fetch_domain_details?api.version=1&domain=indietech.io";
    $curl = curl_init();
     curl_setopt($curl, CURLOPT_HTTPHEADER, [
        "Authorization: whm $whm_user:$whm_token"
    ]);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $header[0] = "Authorization: whm $whm_user:$whm_token";
    curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
    curl_setopt($curl, CURLOPT_URL, $query);

    $result = curl_exec($curl);
     if (curl_errno($curl)) {
        return new WP_REST_Response(['error' => curl_error($curl)], 500);
    }

    curl_close($curl);
    $data = json_decode($result, true);

    return new WP_REST_Response($data, 200);
}

function mcd_rest_create_subdomain(WP_REST_Request $request) {
    $subdomain  = sanitize_text_field($request->get_param('subdomain')) ?? '';
    $rootdomain = sanitize_text_field($request->get_param('rootdomain')) ?? 'indietech.io'; 
    $cpanelHost = 'https://165-227-221-35.cprapid.com:2083';
    $cpanelUser = 'builderlab';
    $apiToken   = 'AOJSAWYXM7WMW5FZ4B1VD76H2F26QS8C';

    if (empty($subdomain) || empty($rootdomain)) {
        return new WP_REST_Response([
            'success' => false,
            'message' => 'required parrameters.'
        ], 400);
    }

    $query = http_build_query([
        'domain' => "$subdomain.$rootdomain",
        'rootdomain' => $rootdomain,
        'dir' => "public_html"
    ]);
    $url = "$cpanelHost/execute/SubDomain/addsubdomain?$query";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: cpanel $cpanelUser:$apiToken"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error    = curl_error($ch);
    curl_close($ch);

    if ($httpCode !== 200 || empty($response)) {
        return new WP_REST_Response([
            'success' => false,
            'message' => 'Failed call cpanel API.',
            'error'   => $error
        ], 500);
    }

    $json = json_decode($response, true);

    if (!empty($json['status']) && $json['status'] == 1) {
        return new WP_REST_Response([
            'success' => true,
            'message' => 'Subdomain success created.',
            'data'    => $json
        ], 200);
    }

    return new WP_REST_Response([
        'success' => false,
        'message' => $json['errors'][0] ?? 'Error.',
        'data'    => $json
    ], 500);
}