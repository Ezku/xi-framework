<?php
class IndexModel extends Xi_Controller_Model
{
    public function getStatus()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return;
        }
        if (isset($request->SuccessButton)) {
            return true;
        } elseif (isset($request->FailureButton)) {
            return false;
        }
    }
}
