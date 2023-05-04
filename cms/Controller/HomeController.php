<?php

namespace Cms\Controller;


class HomeController extends CmsController
{
    public function index()
    {
        $this->load->model('Menu','Menu');

        $this->data['menu'] = $this->model->menu->getList();


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://gzpt.salesdrive.me/report-managers/?filter%255Bfrom%255D=2023-04-01&filter%255Bto%255D=2023-04-20&formId=1',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: lang=ua_UA; _ga=GA1.2.1019505843.1682018435; _gid=GA1.2.1004343761.1682018435; _gat=1; PHPSESSID=2236f165104206fbf4d157f7315cb43b; login=gzpt.com.ua@gmail.com; _identity=[1,"L3b2yybbG2c-8wHmtJ9p5L8gCQ-A3V-1xOp1MzlLN5rYjPVFMKNTkONxu1fbNGacCg66ka0G7yFlT1hCerMQRH82IkfG96r7gkKF",2592000]; _csrf=6a5VAMX7J3Tkdj8-J2-SwTGWvS901Ujr; authSubdomain=gzpt; userId=1; _identity=%5B1%2C%22L3b2yybbG2c-8wHmtJ9p5L8gCQ-A3V-1xOp1MzlLN5rYjPVFMKNTkONxu1fbNGacCg66ka0G7yFlT1hCerMQRH82IkfG96r7gkKF%22%2C2592000%5D'
            ),
        ));

        $response = curl_exec($curl);

        $decode = json_decode($response, true);

        $employees = $decode['meta']['fields']['userId']['references'];
        $totalEmployeesOrders = $decode['data'];

        foreach($decode['data'] as $item) {
            $total[$item['id']] =  $item['totalCount'];
        }

         foreach($employees as $key => $worker) {
             $this->data['employees'][$key] = [
                 'name' => $worker,
                 'total' => $total[$key]
                 ];
         }


         
         
         dd($total, $employees);
         
        curl_close($curl);

        $all_employees = $decode['meta']['fields']['userId']['references'];

        $not_active_employees = [
            1 => "Пригощайся ",
            2 => "Руслана Макарова",
            4 => "Юрій Петрина",
        ];
        
        //$this->data['employees'] = array_diff($all_employees, $not_active_employees);
        //dd($this->data['employees']);
        $this->view->render('index', $this->data);

    }


    public function catalog($data_start = '2023-05-01', $data_end = '2023-05-04')
    {
        
        

        $urls = [
            'order' => "https://gzpt.salesdrive.me/report-managers/?filter%5Bfrom%5D=$data_start&filter%5Bto%5D=$data_end&formId=1",
            'call' => "https://gzpt.salesdrive.me/report-call-managers/?filter%5Bfrom%5D=$data_start&filter%5Bto%5D=$data_end&formId=1",
            'payments' => "https://gzpt.salesdrive.me/orders/?filter%5BorderTime%5D%5Bfrom%5D=$data_start+00:00:00&filter%5BorderTime%5D%5Bto%5D=$data_end+23:59:59&filter%5BpayedAmount%5D%5Bfrom%5D=1&filter%5BstatusId%5D%5B%5D=__NOTDELETED__&formId=1&mode=orderList",
            'add_sales' => "https://gzpt.salesdrive.me/report-products/managers/?filter%5Bfrom%5D=$data_start&filter%5Bto%5D=$data_end&formId=1"
        ];


        $multi = curl_multi_init();

        $channels = [];

        foreach ($urls as $key => $url) {
            $ch = curl_init();

            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_HEADER => $key == 'payments',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Cookie: _ga=GA1.2.190215059.1668150371; login=gzpt.com.ua@gmail.com; _identity=[1,"L3b2yybbG2c-8wHmtJ9p5L8gCQ-A3V-1xOp1MzlLN5rYjPVFMKNTkONxu1fbNGacCg66ka0G7yFlT1hCerMQRH82IkfG96r7gkKF",2592000]; userId=1; lang=ru_RU; PHPSESSID=fa8531615eb3732b4d84b6fce7902a0e; _csrf=zk_xj9e1AUODpkVZjQifFy2MsrAYJPbF; SL_G_WPT_TO=uk; SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1; PHPSESSID=7d275b892b633ab2838c1c29a78597af; _identity=%5B1%2C%22L3b2yybbG2c-8wHmtJ9p5L8gCQ-A3V-1xOp1MzlLN5rYjPVFMKNTkONxu1fbNGacCg66ka0G7yFlT1hCerMQRH82IkfG96r7gkKF%22%2C2592000%5D'
                ],

            ]);

            if ($key == 'payments') {
                curl_setopt($ch, CURLOPT_NOBODY, true);
            }

            curl_multi_add_handle($multi, $ch);

            $channels[$key] = $ch;

        }



//$active = null;

        do {
            $mrc = curl_multi_exec($multi, $active);

        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            if (curl_multi_select($multi) == -1) {
                continue;
            }
            do {
                $mrc = curl_multi_exec($multi, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }

        foreach ($channels as $key => $channel) {

            $$key = curl_multi_getcontent($channel);
            curl_multi_remove_handle($multi, $channel);
        }


        //dd($payments);


        preg_match_all('/Total-Count: ([0-9]+)/', $payments, $matches);
        
        curl_multi_close($multi);





$response = json_decode($order, true);
$response2 = json_decode($call, true);
$response3 = json_decode($add_sales, true);

$user = $response['meta']['fields']['userId']['references'];
$calls = $response2['data'];
$addSales = $response3['manager']['data'];



$managerCalls = [];

foreach ($calls as $call) {
    $managerCalls[$call['id']] = $call['callCount']['totalCount'];
}

$managerAddSales = [];

foreach ($addSales as $addSale) {
    $managerAddSales[$addSale['id']] = $addSale['sumPreSale'];
}

//dd($managerAddOrders);
$onlineStoreEmployee = [
    3,
    6,
    7
];

$oflineStoreEmployee = [
    3
];

$socialNetwork = [
    7
];


$employeToRender = [3, 6, 7];

$this->data['employees'] = [];

foreach ($response['data'] as $item) {
    if (in_array($item['id'], $employeToRender )) {
        $this->data['employees'][$item['id']] = [
            'id' => $item['id'],
            'name' => $user[$item['id']] ?? null,
            'totalOrderCount' => $item['paymentCount'],
            'totalCallCount' => $managerCalls[$item['id']] ?? null,
            'percentOfCalls' => empty($managerCalls[$item['id']]) ? null : round((($managerCalls[$item['id']] / $item['totalCount']) * 100), 0, PHP_ROUND_HALF_UP),
            'addSales' => $managerAddSales[$item['id']] ?? null,
            'payments' => [
                'onlineStoreOrderWork' => in_array($item['id'], $onlineStoreEmployee) ? 5000 : null,
                'oflineStoreWork' => in_array($item['id'], $oflineStoreEmployee) ? 5000 : null,
                'socialNetworkrWork' => in_array($item['id'], $socialNetwork) ? 5000 : null,
                'paymentTotalOrderCount' => $item['totalCount'] * 3,
                'paymentForCalls' => empty($managerCalls[$item['id']]) ? null : round((($managerCalls[$item['id']] / ($item['totalCount'] / 100 * 60)) * 100), 0) * (2000 / 100),
                'processPayments' => ($item['id'] == 3) ? (int)$matches[1][0] : null,
                'percentOfSales' => (($managerAddSales[$item['id']] / 100) * 5) ?? null,   
            ],
            'totalPayments' => 0
        ];    
    }
}

foreach ($this->data['employees'] as $id => $value) {
$num = 0;
   foreach($value['payments'] as $payment) {
    $num += $payment;
   }
   $this->data['employees'][$id]['totalPayments'] = $num;
}



//dd( $this->data['employees']);
$this->view->render('index', $this->data);


    }

}

