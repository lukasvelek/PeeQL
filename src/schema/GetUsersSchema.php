<?php

namespace PeeQL\Schema;

class GetUsersSchema extends ASchema {
    protected function define() {
        $this->addMultipleColumns([
            'userId',
            'username',
            'fullname'
        ]);
    }
}

?>