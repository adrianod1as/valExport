<?php

    $DS = DIRECTORY_SEPARATOR;

    require_once(dirname(__FILE__) .  $DS . "register.php");

    //registro 40
    class InstructorDocumentsAndAddressValidation extends Register {
        
        //campo 5
        function isCPFValid($cpf) {
            if ($cpf == null) {
                return array("status" => false,"erro" => "O campo Número do CPF é uma informação obrigatória.");
            }
            if (strlen($cpf) > 11) {
                return array("status" => false,"erro" => "O campo Número do CPF está com tamanho diferente do especificado.");
            }
            // se nao for numerico
            if (!is_numeric($cpf)) {
                return array("status" => false,"erro" => "O campo Número do CPF foi preenchido com valor inválido.");
            } else if(preg_match('/^(.)\1*$/',$cpf)) {
            return array("status" => false,"erro" => "O campo Número do CPF foi preenchido com valor inválido.");
            } else if($cpf=="00000000191") {
            return array("status" => false,"erro" => "O campo Número do CPF foi preenchido com valor inválido.");
            }
            return array("status"=>true,"erro"=>"");
        }

    //campo 6
    function isAreaOfResidenceValid($area_of_residence) {
        if (strlen($area_of_residence) != 1) {
            return array("status" => false,"erro" => "O campo Localizacao/Area de Residencia foi preenchido com tamanho invalido");
        }
        if ($area_of_residence != 1 || $area_of_residence != 2) {
            return array("status" => false,"erro" => "O campo Localizacao/Area de Residencia  foi preenchido com valor inválido.");
        }
        return array("status" => true,"erro" =>"");
    }

    //campo 7
    function isCEPValid($cep) {
        if ($cep == null) {
            return array("status" => false,"erro" => "O campo CEP é uma informação obrigatória.");
        }
        if ((count($cep) != 8)) {
            return array("status" => false,"erro" => "O campo CEP está com tamanho diferente do especificado.");
        }
        if (!is_numeric($cep)) {
            return array("status" => false,"erro" => "O campo CEP foi preenchido com valor inválido.");
        } else if (preg_match('/^(.)\1*$/', $cep)) {
            return array("status" => false,"erro" => "O campo CEP foi preenchido com valor inválido.");
        }
        return array("status" => true,"erro" =>"");
    }

    //campo 8, 9, 10, 11, 12, 13
    function isAdressValid($field, $cep, $allowed_lenght) {
        $regex = "/^[0-9 a-z.,-ºª ]+$/";
        if ($cep == null) {
            if ($field == null) {
                return array("status" => false,"erro" => "O campo não pode ser nulo.");
            }
        } else if (strlen($field) > $allowed_lenght || strlen($field) <= 0) {
            return array("status" => false,"erro" => "O campo está com tamanho incorreto.");
        } else if (!preg_match($regex, $field)) {
            return array("status" => false,"erro" => "O campo foi preenchido com valor inválido.");
        }
        return array("status" => true,"erro" =>"");
    }
}
?>