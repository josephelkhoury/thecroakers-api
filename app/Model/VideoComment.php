<?php


class VideoComment extends AppModel
{

    public $useTable = 'video_comment';

    public $belongsTo = array(
        'Video' => array(
            'className' => 'Video',
            'foreignKey' => 'video_id',
        ),

        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),
    );

    public $hasMany = array(
        'VideoCommentReply' => array(
            'className' => 'VideoComment',
            'foreignKey' => 'comment_id',
        ),
    );

    public function getDetails($id)
    {
        return $this->find('first', array(
            'conditions' => array(
                'VideoComment.id'=> $id,
            )
        ));
    }

    public function countComments($video_id)
    {
        return $this->find('count', array(
            'conditions' => array(
                'VideoComment.video_id'=> $video_id,
            )
        ));
    }

    public function getVideoComments($video_id,$starting_point)
    {
        $this->Behaviors->attach('Containable');
        return $this->find('all', array(
            'conditions' => array(
                'VideoComment.video_id' => $video_id,
                'VideoComment.comment_id' => 0
            ),

            'limit' => 10,
            'offset' => $starting_point*10,
            'order' => 'VideoComment.id DESC',
            'contain'=>array('Video', 'User.PrivacySetting', 'VideoCommentReply', 'VideoCommentReply.User')
        ));
    }

    public function getAll()
    {
        return $this->find('all');
    }
    
    public function deleteCommentsAgainstCommentID($comment_id) {
        $conditions = array(
           	'VideoComment.comment_id' => $comment_id
        );
        $this->deleteAll($conditions);
    }
}
?>