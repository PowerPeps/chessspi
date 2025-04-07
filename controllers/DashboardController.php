<?php
class DashboardController extends Controller {
    public function index() {
        $this->view('dashboard/index');
    }

    public function leaderboard(){
        $this->view('dashboard/leaderboard');
    }
}