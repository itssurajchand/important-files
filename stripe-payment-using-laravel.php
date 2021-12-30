
use Stripe;
try {
  Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
  $error = '';
  $success = '';
  $create_charge = Stripe\Charge::create ([
    "amount" => $total_amount * 100,
    "currency" => $currency,
    "source" => $stripeToken,
    "description" => "This payment is done by "
  ]);
  $charge_id = $create_charge->id;

    $success = 1;
}
catch(\Stripe\Exception\CardException $e) {
  $error = $e->getError()->message;
} catch (\Stripe\Exception\RateLimitException $e) {
  // Too many requests made to the API too quickly
    $error = $e->getError()->message;
} catch (\Stripe\Exception\InvalidRequestException $e) {
  // Invalid parameters were supplied to Stripe's API
    $error = $e->getError()->message;
} catch (\Stripe\Exception\AuthenticationException $e) {
  // Authentication with Stripe's API failed
    $error = $e->getError()->message;
  // (maybe you changed API keys recently)
} catch (\Stripe\Exception\ApiConnectionException $e) {
  // Network communication with Stripe failed
    $error = $e->getMessage();
} catch (\Stripe\Exception\ApiErrorException $e) {
  // Display a very generic error to the user, and maybe send
  // yourself an email
    $error = $e->getMessage();
} catch (Exception $e) {
  // Something else happened, completely unrelated to Stripe
    $error = $e->getMessage();

}

$insert = ZohoCreditPayment::insert($dataArray);
if ($success!=1)
{
   Session::flash('error', $error);
    return back();
}
