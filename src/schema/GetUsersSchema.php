<?php

namespace PeeQL\Schema;

class GetUsersSchema extends AQuerySchema {
    protected function define() {
        $this->addMultipleColumns([
            'userId',
            'username',
            'fullname'
        ]);
    }
}

?>