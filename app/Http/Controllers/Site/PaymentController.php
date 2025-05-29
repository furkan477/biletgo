<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\FlightTicketUsersDataRequest;
use App\Http\Requests\SelectFlightRequest;
use App\Http\Requests\TicketPaymentRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PassengerInformation;
use App\Models\TicketOrder;
use App\Models\User;
use Auth;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Str;
use Stripe\Charge;
use Illuminate\Support\Facades\Http;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        $dt = [
            "flights" => $request->flights,
            "id" => $request->data_id,
        ];

        $response = Http::accept('application/json')->withUserAgent('Test/Test')->post("https://sorgulamax.com/select-flight", $dt);
        $ticket = json_decode($response->getBody(), true);

        $total_price = 0;
        foreach ($ticket['data']['fares'] as $passenger => $info) {
            $price = ($info['baseFare'] + $info['tax'] + $info['serviceFee']) * $info['quantity'];
            $total_price += $price;
        }

        $oid = uniqid('oid_');

        PassengerInformation::create([
            'oid' => $oid,
            'reservation_data' => json_encode([
                "id" => $request->data_id,
                "flights" => $request->flights,
                "passengers" => $request->passengers,
                "number" => $request->phone_number,
                "email" => $request->email,
                "holder_name" => "Furkan Test",
                "card_number" => $request->pan,
                "expiry_month" => $request->Ecom_Payment_Card_ExpDate_Month,
                "expiry_year" => $request->Ecom_Payment_Card_ExpDate_Year,
                "cvv" => $request->cvv,
                "payment_method" => "virtualPos",
                "type" => $request->fatura_tipi,
                "name" => $request->name_invoice,
                "citizen_id" => $request->citizen_id_invoice,
                "tax_office" => $request->tax_office,
            ]),
        ]);

        $data = [
            "amount" => $total_price,
            "BillToCompany" => "BiletGo",
            "trantype" => "Auth",
            "BillToName" => "BiletGo",
            "callbackUrl" => route('payment.callback'),
            "cardType" => $request->cardType,
            "clientid" => env('ZIRAAT_CLIENT_ID'),
            "currency" => 949,
            "cvv" => $request->cvv,
            "Ecom_Payment_Card_ExpDate_Month" => $request->Ecom_Payment_Card_ExpDate_Month,
            "Ecom_Payment_Card_ExpDate_Year" => $request->Ecom_Payment_Card_ExpDate_Year,
            "failurl" => route('payment.callback'),
            "hashAlgorithm" => "ver3",
            "instalment" => "",
            "lang" => "tr",
            "oid" => $oid,
            "okurl" => route('payment.callback'),
            "pan" => $request->pan,
            "rnd" => uniqid(),
            "storetype" => "3d",
        ];

        $data['HASH'] = $this->hash($data);

        \Log::info('Bankaya gelen veri: ', $data);

        return view('redirect', compact('data'));
    }

    private function hash(array $data): string
    {
        uksort($data, function ($a, $b) {
            return strcasecmp($a, $b); // Büyük/küçük harf duyarsız karşılaştırma
        });
        $hashString = implode('|', $data) . '|' . env('ZIRAAT_STORE_KEY');
        $sha512Hash = hash("sha512", $hashString); // true parametresi binary format döndürür
        return base64_encode(pack('H*', $sha512Hash));
    }


    // Rezervasyon oluşturma işlemi oluşturulsun api istek atılsın. bilgiler request validate geçsin

    public function handleCallback(Request $request)
    {
        //.... once requestten gelen dataları dogrula. bankadan geldiginden emin oldugunda (hash algoritmasını geçerse, senin ürettigin hash ile bankladan gelen hash aynı olursa) o zaman aşağıdaki işlemleri yaptır.

        if ($this->hash($request->except("HASH")) != $request->get('HASH')) {
            return response()->json(['error' => 'Hash dogrulanamadı.']);
        }

        $data = $request->all();
        
        $clientid = env('ZIRAAT_CLIENT_ID');
        $username = env('ZIRAAT_API_USERNAME');
        $password = env('ZIRAAT_API_PASSWORD');
        $txnType = "Auth";

        if (!in_array($data['mdStatus'], ["1"])) {
            return response()->json(["error" => false, "message" => "3D doğrulama başarısız."]);
        }

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
         <CC5Request>
             <Name>{$username}</Name>
             <Password>{$password}</Password>
             <ClientId>{$clientid}</ClientId>
             <Type>{$txnType}</Type>
             <Total>{$data['amount']}</Total>
             <Currency>949</Currency>
             <Taksit></Taksit>
             <Number></Number>
             <Expires></Expires>
             <Cvv2Val></Cvv2Val>
             <PayerTxnId>{$data['xid']}</PayerTxnId>
             <PayerSecurityLevel>{$data['eci']}</PayerSecurityLevel>
             <PayerAuthenticationCode>{$data['cavv']}</PayerAuthenticationCode>
             <CardholderPresentCode>13</CardholderPresentCode>
             <Mode>P</Mode>
             <OrderId>{$data['oid']}</OrderId>
             <GroupId></GroupId>
             <TransId></TransId>
         </CC5Request>";

        $xmlresponse = Http::withHeaders(['Content-Type' => 'application/x-www-form-urlencoded'])
            ->asForm()
            ->post(
                env('ZIRAAT_API_URL'),
                ['data' => $xml]
            );

        $response = simplexml_load_string($xmlresponse->body());

        $passenger = PassengerInformation::where('oid',$data['oid'])->first();
        $reservation = json_decode($passenger->reservation_data, true);
            
        $reservationAndPay = [
            "id" => $reservation['id'],
            "flights" => $reservation['flights'],
            "passengers" => [],
            "phone" => [
                "number" => $reservation['number']
            ],
            "email" => $reservation['email'],
            "holder_name" => "Furkan Test",
            "card_number" => $reservation['card_number'],
            "expiry_month" => $reservation['expiry_month'],
            "expiry_year" => $reservation['expiry_year'],
            "cvv" => $reservation['cvv'],
            "payment_method" => "virtualPos",
            "packages" => [],
            "invoice_information" => [
                "type" => $reservation['type'],
                "name" => $reservation['name'],
                "id" => $reservation['citizen_id'],
                "tax_office" => $reservation['tax_office']
            ],
        ];

        $passengers = $reservation['passengers'];
        foreach ($passengers as $passenger) {
    
            $year = $passenger['birthday_year'] ?? '1993';
            $month = $passenger['birthday_month'] ?? '01';
            $day = $passenger['birthday_day'] ?? '01';

            $reservationAndPay['passengers'][] = [
                "type" => $passenger['type'],
                "is_foreign" => $passenger['citizen_id'] ? '0' : '1',
                "gender" => $passenger['gender'],
                "name" => $passenger['name'],
                "surname" => $passenger['surname'],
                "citizen_id" => $passenger['citizen_id'],
                "birthdate" => $year . '-' . $month . '-' . $day,
            ];
        }

        $responsee = Http::accept('application/json')->withUserAgent('Test/Test')->post("https://sorgulamax.com/reservation", $reservationAndPay);
        $dataa = json_decode($responsee->getBody(), true);
        
        TicketOrder::create([
            'user_id' => 1,
            'reservation_data' => json_encode([
                'view_link' => $dataa['data']['view_link'],
                'pay_link' => $dataa['data']['pay_link'],
                'retrieve_link' => $dataa['data']['retrieve_link'],
                'status' => $dataa['data']['status'],
                'id' => $dataa['data']['id'],
                'email' => $dataa['data']['email'],
                'phone' => $dataa['data']['phone'],
                'invoice_information' => $dataa['data']['invoice_information'],
                'flights' => $dataa['data']['flights'],
                'passengers' => $dataa['data']['passengers'],
                'created_at' => $dataa['data']['created_at'],
            ]),
        ]);

        return view('site.pages.paymentresult', compact('response'));


    }

}
