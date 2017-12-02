<?php

namespace Drupal\yuncheng_admin\Termtree;
use Drupal\Core\Entity\EntityTypeManager;
use \Drupal\taxonomy\Entity\Term;

/**
 * Loads taxonomy terms in a tree
 */
class TaxonomyTermTree {
    /**
     * @var \Drupal\Core\Entity\EntityTypeManager
     */
    protected $entityTypeManager;
    protected $term_storage;

    /**
     * TaxonomyTermTree constructor.
     *
     * @param \Drupal\Core\Entity\EntityTypeManager $entityTypeManager
     */
    public function __construct() {
        //$this->entityTypeManager = $entityTypeManager;
        $this->term_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
    }

    /**
    * Utility: find term by name and vid.
    * @param null $name
    *  Term name
    * @param null $vid
    *  Term vid
    * @return int
    *  Term id or 0 if none.
    */
    public function getTidByName($name = NULL, $vid = NULL) {
        $properties = [];
        if (!empty($name)) {
          $properties['name'] = $name;
        }
        if (!empty($vid)) {
          $properties['vid'] = $vid;
        }
        $terms = $this->term_storage->loadByProperties($properties);
        $term = reset($terms);

        //\Drupal::logger('term')->notice('$term: <pre>@data</pre>', array('@data' => print_r($term, true)));
        //$parent = $this->term_storage->loadParents($term->id());
        
        $term_root = $this->term_storage->loadTree($vid, $term->id(), 1);
        $build_tree = [];

        if ( !count($term_root) ) {

            //$term = $this->term_storage->load(9);

            $term_no_child = $this->term_storage->load($term->id());
            //$term = Term::load(9);

            $build_tree[$term->id()]->tid = $term_no_child->id();
            $build_tree[$term->id()]->vid = $term_no_child->getVocabularyId();
            $build_tree[$term->id()]->name = $term_no_child->getName();
            $build_tree[$term->id()]->children = [];

        } else {
            foreach ($term_root as $term) {
                $this->build_child_term_tree($build_tree, $term, $vid, 1);
            }
        }

        return $build_tree;
    }

    /**
     * Populates a tree array given a taxonomy term tree object.
     *
     * @param $tree
     * @param $object
     * @param $vocabulary
     */
    protected function build_child_term_tree(&$build, $object, $vid, $max_leve) {

        $child_tree = $this->term_storage->loadTree($vid, $object->tid, 1);

        //ksm($object->tid);
        $build[$object->tid] = $object;
        $build[$object->tid]->children = [];
        $object_children = &$build[$object->tid]->children;

        if ( !count($child_tree) ) {
            return ; 
            
        } else {

            foreach ($child_tree as $childObject) {
                $this->build_child_term_tree($object_children, $childObject, $vid, 1);
            }
        }

    }
    /**
     * Loads the shiti tag tree of a vocabulary.
     *
     * @param string $vocabulary
     *   Machine name
     *
     * @return array
     */
    public function load_shiti_tag($voc) {
        $term_root = $this->term_storage->loadTree($voc);
        $tree = [];
        foreach ($term_root as $tree_object) {
            $this->timu_tag_buildTree($tree, $tree_object, $voc);
        }
 
        return $tree;
    }


    protected function timu_tag_buildTree(&$tree, $object, $vocabulary) {
        if ($object->depth != 0) {
            return;
        }
        $tree[$object->tid] = $object;
        $tree[$object->tid]->children = [];
        $object_children = &$tree[$object->tid]->children;
     
        $children = $this->term_storage->loadChildren($object->tid);
        if (!$children) {
            return;
        }
     
        $child_tree_objects = $this->term_storage->loadTree($vocabulary, $object->tid);
     
        foreach ($children as $child) {
            foreach ($child_tree_objects as $child_tree_object) {
                if ($child_tree_object->tid == $child->id()) {
                    $this->timu_tag_buildTree($object_children, $child_tree_object, $vocabulary);
                }
            }
        }
    }


    /**
     * Loads the tree of a vocabulary.
     *
     * @param string $vocabulary
     *   Machine name
     *
     * @return array
     */
    public function load($vocabulary) {
        $term_root = $this->term_storage->loadTree($vocabulary, 0, 1);
        $build_tree = [];

        foreach ($term_root as $term) {
            $this->buildTree($build_tree, $term, $vocabulary, 1);
        }
        return $build_tree;
    }
    /**
     * Populates a tree array given a taxonomy term tree object.
     *
     * @param $tree
     * @param $object
     * @param $vocabulary
     */
    public function buildTree(&$build, $object, $vid, $max_leve) {

        $child_tree = $this->term_storage->loadTree($vid, $object->tid, 1);
        $node_item = new term_node();
        $node_item->text = $object->name;
        $node_item->href = "javascript:void(0)";
        $node_item->tags = [$object->tid];

        if ( !count($child_tree) ) {
            $build[] = $node_item;
        } else {
            $node_item->nodes = [];

            foreach ($child_tree as $childObject) {
                $this->buildTree($node_item->nodes, $childObject, $vid, 1);
            }
            $build[] = $node_item;
        }

    }


}

class term_node {
    public $text;
    public $href;
    public $tags;
}

