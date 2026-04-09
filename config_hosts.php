<?php
// Liste d'hôtes publics à tester (accessibles depuis Internet)
return [
    [
        'name' => 'Google',
        'ip' => '8.8.8.8',
        'url' => 'https://www.google.com',
        'snmp_host' => '8.8.8.8',
        'snmp_community' => 'public'
    ],
    [
        'name' => 'GitHub API',
        'ip' => '140.82.112.3',
        'url' => 'https://api.github.com',
        'snmp_host' => '140.82.112.3',
        'snmp_community' => 'public'
    ],
    [
        'name' => 'Cloudflare',
        'ip' => '1.1.1.1',
        'url' => 'https://1.1.1.1',
        'snmp_host' => '1.1.1.1',
        'snmp_community' => 'public'
    ],
    [
        'name' => 'Mon site InfinityFree',
        'ip' => 'herlich.free.nf',
        'url' => 'http://herlich.free.nf',
        'snmp_host' => 'herlich.free.nf',
        'snmp_community' => 'public'
    ],
];
