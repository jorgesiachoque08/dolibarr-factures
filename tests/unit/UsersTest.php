<?php

class UsersTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $user;
    
    protected function _before()
    {
        $this->user = new OauthController();
    }

    protected function _after()
    {
    }

    // tests
    public function testLoginSuccess()
    {
        $result = $this->user->loginActiveDirectory("jorge.siachoque","Bodytech2021*");
        $this->assertContains("success",$result);
        //print_r($ctrl);die;
        //$this->assertArrayHasKey('errors', $ctrl);// validad key;
        //assertArrayNotHasKey() // si eno existe la key en el arreglo
       //print_r($ctrl);die;
        /* $this->assertContains("i",$ctrl);// valida un valor en un arraya
        $this->assertFalse(false);/si el valor es false
        $this->assertTrue(true); *///si el valor es true
        //$this->assertNull($ctrl); //si el valor es null
        //$this->assertEmpty($ctrl); //0,"","0",false,array(),null
    }

    // tests
    public function testLoginError()
    {
        $result = $this->user->loginActiveDirectory("jorge.siachoaque","Bodytech2021*");
        $this->assertContains("error",$result);
        //print_r($ctrl);die;
        //$this->assertArrayHasKey('errors', $ctrl);// validad key;
        //assertArrayNotHasKey() // si eno existe la key en el arreglo
       //print_r($ctrl);die;
        /* $this->assertContains("i",$ctrl);// valida un valor en un arraya
        $this->assertFalse(false);/si el valor es false
        $this->assertTrue(true); *///si el valor es true
        //$this->assertNull($ctrl); //si el valor es null
        //$this->assertEmpty($ctrl); //0,"","0",false,array(),null
    }
}