<?php
class IndexView extends Xi_Controller_View
{
    public function displayIndex()
    {
        $this->message = 'This is a view without a status';
    }

    public function displayIndexSuccess()
    {
        $this->message = 'This was a success';
    }

    public function displayIndexFailure()
    {
        $this->message = 'This was a failure';
    }
}
