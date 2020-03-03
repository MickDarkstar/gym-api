<?php

/**
 * MuscleController
 */
final class MuscleController extends BaseController
{
    private $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new MuscleService();
    }

    public function All()
    {
        parent::Authorize();

        $result = $this->service->getAll();
        echo ApiResponse::Ok("All muscles", $result);
    }
}
