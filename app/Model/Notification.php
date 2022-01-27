<?php


class Notification extends AppModel
{
    public $useTable = 'notification';


    public $belongsTo = array(
        'Video' => array(
            'className' => 'Video',
            'foreignKey' => 'video_id',



        ),

        'Sender' => array(
            'className' => 'User',
            'foreignKey' => 'sender_id',



        ),

        'Receiver' => array(
            'className' => 'User',
            'foreignKey' => 'receiver_id',



        ),


    );


    public function getDetails($id)
    {
        return $this->find('first', array(
            'conditions' => array('Notification.id' => $id)
        ));

    }



    public function getAll()
    {

        return $this->find('all');

    }


    public function getUserNotifications($user_id,$starting_point)
    {
        return $this->find('all', array(
            'conditions' => array(
                'Notification.receiver_id' => $user_id
            ),
            'order' => 'Notification.id DESC',
            'limit'=>10,
            'offset' => $starting_point*10,
        ));
    }
    
    public function deleteNotificationsAgainstUserID($user_id) {
        $conditions = array(
            'OR' => array(
                'Notification.sender_id' => $user_id,
                'Notification.receiver_id' => $user_id,
            )
        );
        $this->deleteAll($conditions);
    }
    
    
	public function deleteNotificationsAgainstVideoID($video_id) {
        $conditions = array(
           	'Notification.video_id' => $video_id
        );
        $this->deleteAll($conditions);
    }

}

?>