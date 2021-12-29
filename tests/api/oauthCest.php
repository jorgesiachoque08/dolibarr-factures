<?php

class oauthCest
{

    public $client_id;
    public $client_secret;
    public $uuid_organization;
    public $tokenUser;
    public $refresh_token;
    public $tokenClient;
  
    public function _before(ApiTester $I)
    {
      $this->client_id = 'grupodtg_athletic_col';
      $this->client_secret = 'a23rd4fwa3343ae345s6ss44hj3ee9sja1234dd4';
      $this->uuid_organization = '7c022047-bfc9-11eb-b063-062b475b052b';

    }

    // tests
    public function testLoginSuccess(ApiTester $I)
    {
      //$I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/oauth/token', [
          'grant_type' => 'password', 
          'client_id' => $this->client_id,
          'client_secret' =>$this->client_secret,
          'username' =>'jorgesiachoque08@gmail.com',
          'password' =>'123456789'
        ]);
        list($access_token) = $I->grabDataFromResponseByJsonPath('$.access_token');
        list($refresh_token) = $I->grabDataFromResponseByJsonPath('$.refresh_token');
        $this->tokenUser = $access_token;
        $this->refresh_token = $refresh_token;
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(array("expires_in"=>21600));//valida que en el json de la respuesta venga esa key con ese valor
    }

      // tests
    public function testSignUpSuccess(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('x-bodytech-client-id', $this->client_id);

        $I->sendPost('/user', ["birthdate"=>"1993-10-12T00:00:00.000Z",
                                      "document_number"=>"1047248278184",
                                      "document_type_id"=>"10",
                                      "email"=>"jorgesiachosqwue08@gmail.com",
                                      "lastname"=>"Prueba",
                                      "name"=>"Usuario",
                                      "password"=>"123456789",
                                      "phone"=>"3147417292",
                                      "platform"=>"Web",
                                      "terms_data"=>true]);

        $I->seeResponseCodeIs(200); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(array("expires_in"=>21600));//valida que en el json de la respuesta venga esa key con ese valor
    }

    // tests
    public function testValidateTokenSuccess(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('x-bodytech-organization', $this->uuid_organization);
        //token user
        $I->amBearerAuthenticated($this->tokenUser);
        $I->sendPost('/validateToken');

        $I->seeResponseCodeIs(200); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(array("status"=>"success"));//valida que en el json de la respuesta venga esa key con ese valor
    }

    // tests
    public function testRefreshTokenSuccess(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/oauth/token',[
          'grant_type' => 'refresh_token', 
          'client_id' => $this->client_id,
          'client_secret' =>$this->client_secret,
          'refresh_token' =>$this->refresh_token
        ]);

        $I->seeResponseCodeIs(200); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(array("expires_in"=>21600));//valida que en el json de la respuesta venga esa key con ese valor
    }

    // tests
    public function testLoginClientSuccess(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/oauth/token',[
          'grant_type' => 'client_credentials', 
          'client_id' => $this->client_id,
          'client_secret' =>$this->client_secret,

        ]);
        list($access_token) = $I->grabDataFromResponseByJsonPath('$.access_token');
        $this->tokenClient = $access_token;

        $I->seeResponseCodeIs(200); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(array("expires_in"=>21600));//valida que en el json de la respuesta venga esa key con ese valor
    }
    
    // tests
    public function testRecoveryPasswordSuccess(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amBearerAuthenticated($this->tokenClient);
        $I->sendGet('/user/recovery-password/jorgesiachoque08@gmail.com');

        $I->seeResponseCodeIs(200); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(array("status"=>"success"));//valida que en el json de la respuesta venga esa key con ese valor
    }
    
    // tests
    public function testPasswordResetSuccess(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/user/password-reset',["token"=> "2a90d0e249cd7eda9e28f95cba1691b9dded90e4",
                                            "password"=>"123456789"]);

        $I->seeResponseCodeIs(200); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(array("status"=>"success"));//valida que en el json de la respuesta venga esa key con ese valor
    }

     // tests
     public function testListCountriesSuccess(ApiTester $I)
     {
         $I->haveHttpHeader('Content-Type', 'application/json');
         $I->amBearerAuthenticated($this->tokenClient);
         $I->sendGet('/listCountries');
 
         $I->seeResponseCodeIs(200); // 200
         $I->seeResponseIsJson();
         $I->seeResponseContainsJson(array("status"=>"success"));//valida que en el json de la respuesta venga esa key con ese valor
     }

      // tests
      public function testListByCountrySuccess(ApiTester $I)
      {
          $I->haveHttpHeader('Content-Type', 'application/json');
          $I->amBearerAuthenticated($this->tokenClient);
          $a = $I->sendGet('/listByCountry/2');
          list($id) =$I->grabDataFromResponseByJsonPath('$.status');
         /*  var_dump(); */
          $I->seeResponseCodeIs(200); // 200
          $I->seeResponseIsJson();
          $I->seeResponseContainsJson(array("status"=>"success"));//valida que en el json de la respuesta venga esa key con ese valor
      }

}
