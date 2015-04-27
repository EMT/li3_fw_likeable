<?php

namespace li3_fw_likeable\traits;
use app\models\Likes;

/**
 * The Likeable trait makes it easy to attach likes to any model entity
 * via one or more fields.
 *
 */

trait Likeable {

	protected static $_likeable_config;


	public static function likeableConfig($settings) {
		self::$_likeable_config = $settings;
	}


	public function like($entity, $user = null) {

		if (!empty(self::$_likeable_config['anonymous_likes']) && $user === NULL) {
			return ['status' => "not logged in"];
		} 
		else {
			try {
				$like = Likes::create();
				$like->user_id = ($user) ? $user->id : null;
				$like->user_ip = $_SERVER['REMOTE_ADDR'];
				$like->liked_entity_id = $entity->id;
				$like->liked_entity_type = $entity->model();
				

				if (!$entity->userLiked($user) && $like->save()) {
					$entity->like_count++;
					
					if ($entity->save(null, ['validate' => false])) {
						return [
							'status' => 'liked',
							'newCount' => $entity->like_count
						];
					} 
				var_dump($entity->errors());	
					return ['status' => 'problem saving on associated model'];
				}

				return ['status' => 'failed'];
			} 
			catch ( \Exception $e) {
				return ['status' => 'database error'];
			}
		}
	}


	public function unlike($entity, $user = null) {
		if (!empty(self::$_likeable_config['anonymous_likes']) && $user === NULL) {
			return ['status' => "not logged in"];
		} 
		else {
			if ($user) {
				$like = Likes::first([
				    'conditions' => [
				    	'user_id' => $user->id,
				    	'liked_entity_id' => $entity->id,
				    	'liked_entity_type' => $entity->model()
				    ]
				]);
			}
			else {
				$like = Likes::first([
				    'conditions' => [
				    	'user_ip' => $_SERVER['REMOTE_ADDR'],
				    	'liked_entity_id' => $entity->id,
				    	'liked_entity_type' => $entity->model()
				    ]
				]);
			}

			if ($like){
				$like->delete();	
				$entity->like_count--;
				
				if ($entity->save(null, ['validate' => false])) {
					return [
						'status' => 'unliked',
						'newCount' => $entity->like_count,
					];
				}

				return ['status' => 'problem saving on associated model'];
			} 
			
			return ['status' => 'not found'];	
		}
	}

	public function userLiked($entity, $user) {
		$likes = Likes::first([
		    'conditions' => [
		    	'user_id' => $user === NULL ? '' : $user->id,
		    	'liked_entity_id' => $entity->id,
		    	'liked_entity_type' => $entity->model()
		    ],
		    'fields' => ['id']
		]);

		if (count($likes)) {
			return true;
		} 
		else {
			return false;
		}
	}

}

?>