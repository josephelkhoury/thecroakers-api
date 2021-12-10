<?php


class TopicFavourite extends AppModel
{
    public $useTable = 'topic_favourite';

    public $belongsTo = array(
        'Topic' => array(
            'className' => 'Topic',
            'foreignKey' => 'topic_id',
        ),

        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),

    );

    public function getDetails($id)
    {
        return $this->find('first', array(
            'conditions' => array(
                'TopicFavourite.id'=> $id,
            )
        ));
    }

    public function getUserFavouriteTopics($user_id,$starting_point)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'contain' => array('Topic','User'),
            'conditions' => array(
                'TopicFavourite.user_id'=> $user_id,
            ),
            'limit' => 10,
            'offset' => $starting_point*10,
        ));
    }

    public function ifExist($data)
    {
        return $this->find('first', array(
            'conditions' => array(
                'TopicFavourite.topic_id'=> $data['topic_id'],
                'TopicFavourite.user_id'=> $data['user_id'],
            )
        ));
    }

    public function getAll()
    {
        return $this->find('all');
    }

}
?>
