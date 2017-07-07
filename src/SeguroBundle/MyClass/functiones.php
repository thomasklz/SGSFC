<?php

namespace SeguroBundle\MyClass; 

class functiones
{
  
   public function DecoJson($response){
   	$contents = $response->getBody()->getContents();
    $response_body = json_decode($contents, true);
    return $response_body;
   }
   
   public function DecoMessage($message){
   		foreach ($message as $key => $respuesta) 
            {
              if ($key=='message')
              {
                return $respuesta;
              }
            }
   }
}

