<?php
use PHPUnit\Framework\TestCase;
require_once  '/sdks/src/Facebook/autoload.php';

class fbTest extends TestCase
{


    public function testCredential()
    {
 
        
        require 'index.php';
        
        $this->assertNotEmpty($app_id);
        $this->assertNotEmpty($app_secret);
        
        $this->assertEquals($default_graph_version,'v2.7');
        
    }

}

?>