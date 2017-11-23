<?php
$host = 'localhost';
$db = 'Milletech';
$user = 'root';
$password = 'root';
$dsn = "mysql:host=$host;dbname=$db;";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false];
$pdo = new PDO($dsn, $user, $password, $options);


$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://www.milletech.se/invoicing/export/customers",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"name\"\r\n\r\nMarcus Dalgren\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"email\"\r\n\r\nmarcus@raket.co\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"birthday\"\r\n\r\n19800307\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"submit\"\r\n\r\nsubmit\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
        "postman-token: 73c8fb11-7ced-7099-f15c-12b0f23149f1"
    ),
));

$response = json_decode(curl_exec($curl), true);
$err = curl_error($curl);

curl_close($curl);


foreach ($response as $customers) {
    $users = $pdo->prepare("INSERT INTO
    `customers` (`id`, `email`, `firstname`, `lastname`, `gender`, `customer_activated`, `group_id`, `customer_company`, 
    `default_billing`, default_shipping, `is_active`, `created_at`, `updated_at`, `customer_invoice_email`, `customer_extra_text`, `customer_due_date_period`) 
  VALUES (
    :id, :email, :firstname, :lastname, :gender, :customer_activated, :group_id, :customer_company, 
    :default_billing, :default_shipping, :is_active, :created_at, :updated_at, :customer_invoice_email, :customer_extra_text, :customer_due_date_period)");

    $address = $pdo->prepare("INSERT INTO `address` (
  `id`, `customer_id`, `customer_address_id`, `email`, `firstname`, `lastname`, `postcode`,
  `street`, `city`, `telephone`, `country_id`, `address_type`, `company`, `country`) 
  VALUES 
  (:id, :customer_id, :customer_address_id, :email, :firstname, :lastname, :postcode, 
  :street, :city, :telephone, :country_id, :address_type, :company, :country)");


    $users->execute([
        ':id' => $customers['id'],
        ':email' => $customers['email'],
        ':firstname' => $customers['firstname'],
        ':lastname' => $customers['lastname'],
        ':gender' => $customers['gender'],
        ':customer_activated' => $customers ['customer_activated'],
        ':group_id' => $customers ['group_id'],
        ':customer_company' => $customers ['customer_company'],
        ':default_billing' => $customers ['default_billing'],
        ':default_shipping' => $customers ['default_shipping'],
        ':is_active' => $customers ['is_active'],
        ':created_at' => $customers ['created_at'],
        ':updated_at' => $customers ['updated_at'],
        ':customer_invoice_email' => $customers ['customer_invoice_email'],
        ':customer_extra_text' => $customers ['customer_extra_text'],
        ':customer_due_date_period' => $customers ['customer_due_date_period']

    ]);

    if (!is_array($customers['address'])) continue;

    $address->execute([
        ':id' => $customers['address'] ['id'],
        ':customer_id' => $customers['address'] ['customer_id'],
        ':customer_address_id' => $customers['address'] ['customer_address_id'],
        ':email' => $customers['address'] ['email'],
        ':firstname' => $customers['address'] ['firstname'],
        ':lastname' => $customers['address'] ['lastname'],
        ':postcode' => $customers['address'] ['postcode'],
        ':street' => $customers['address'] ['street'],
        ':city' => $customers['address'] ['city'],
        ':telephone' => $customers['address'] ['telephone'],
        ':country_id' => $customers['address'] ['country_id'],
        ':address_type' => $customers['address'] ['address_type'],
        ':company' => $customers['address'] ['company'],
        ':country' => $customers['address'] ['country']

    ]);
}
echo "data korrekt";



