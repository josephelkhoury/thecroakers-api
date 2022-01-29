<?php


class ShareLink extends AppModel
{

    public $useTable = 'share_link';


    public function getDetails($link) {
        return $this->find('first', array(
            'conditions' => array(
                'ShareLink.link'=> $link
            )
        ));
    }

}
?>