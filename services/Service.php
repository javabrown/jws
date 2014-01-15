<?php
abstract class Service {
        abstract public function validate($errors);
        abstract public function perform($errors);
}
?>