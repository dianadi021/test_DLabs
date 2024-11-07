<?php

namespace App\Http\Responses;

class ResponseCode
{
    public function OKE()
    {
        return 200;
    }
    public function CREATED()
    {
        return 201;
    }
    public function ACCEPTED()
    {
        return 202;
    }
    public function NOT_FOUND()
    {
        return 404;
    }
    public function FORBIDDEN()
    {
        return 403;
    }
    public function BAD_REQUEST()
    {
        return 400;
    }
    public function TO_MANY_REQ()
    {
        return 429;
    }
    public function SERVER_ERROR()
    {
        return 500;
    }
    public function UNAUTHORIZED()
    {
        return 401;
    }
    public function SERVER_TIMEOUT()
    {
        return 504;
    }
}
