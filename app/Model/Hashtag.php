<?php


class Hashtag extends AppModel
{
    public $useTable = 'hashtag';

    public function getDetails($id)
    {
        return $this->find('first', array(
            'conditions' => array(
                'Hashtag.id'=> $id,
            )
        ));
    }

    public function ifExist($name)
    {
        return $this->find('first', array(
            'conditions' => array(
                'Hashtag.name'=> $name,
            )
        ));
    }

    public function getFeaturedHashtags()
    {
	$this->Behaviors->attach('Containable');

      	return $this->find('all', array(
	    'conditions' => array(
	      'Hashtag.featured' => true
	    ),
            'order' => 'Hashtag.id ASC',
            'recursive'=>-1
        ));
    }

    public function getSearchResults($keyword,$starting_point){
        return $this->find('all', array(

            'conditions' => array(
                'Hashtag.name Like' => "$keyword%"
            ),

            'limit' => 10,
            'offset' => $starting_point*10,
            'recursive' => 0
        ));

    }

}
?>
