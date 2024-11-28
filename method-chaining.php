<?php 
class Person{
    private $name;
    private $age;
    private $email;

    function setName($name){
        $this->name = $name;
        return $this;
    }

    function setAge($age){
        $this->age = $age;
        return $this;
    }

    function setEmail($email){
        $this->email = $email;
        return $this;
    }

    function introduce(){
        echo "My name is {$this->name}. I am {$this->age} years old.";
        if($this->email){
            echo " You can contact me at {$this->email}";
        }
    }
}

$person = (new Person)->setName('John Doe')->setAge(30)->setEmail('john@doe.com')->introduce();
