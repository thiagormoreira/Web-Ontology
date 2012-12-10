<?php
/**
 * Wrap a librdf_iterator as a PHP iterator using SPL.
 *
 * PHP version 5
 *
 * Copyright (C) 2006, David Shea <david@gophernet.org>
 *
 * LICENSE: This package is Free Software and a derivative work of Redland
 * http://librdf.org/.  This package is not endorsed by Dave Beckett or the 
 * University of Bristol. It is licensed under the following three licenses as 
 * alternatives:
 *   1. GNU Lesser General Public License (LGPL) V2.1 or any newer version
 *   2. GNU General Public License (GPL) V2 or any newer version
 *   3. Apache License, V2.0 or any newer version
 *
 * You may not use this file except in compliance with at least one of the
 * above three licenses.
 *
 * See LICENSE.txt at the top of this package for the complete terms and futher
 * detail along with the license tests for the licenses in COPYING.LIB, COPYING
 * and LICENSE-2.0.txt repectively.
 *
 * @package     LibRDF
 * @author      David Shea <david@gophernet.org>
 * @copyright   2006 David Shea
 * @license     LGPL/GPL/APACHE
 * @version     Release: 1.0.0
 * @link        http://www.gophernet.org/projects/redland-php/
 */

/**
 */
require_once(dirname(__FILE__) . '/Statement.php');

/**
 * Wrap a librdf_iterator resource as an iterable object.
 *
 * Objects of this type may only be used for iteration once.  Once iteration
 * has begun, a call to rewind will render the object invalid.
 *
 * @package     LibRDF
 * @author      Felix Ostrowski <felix.ostrowski@googlemail.com>
 * @author      David Shea <david@gophernet.org>
 * @copyright   2006 David Shea, 2012 Felix Ostrowski
 * @license     LGPL/GPL/APACHE
 * @version     Release: 1.0.0
 * @link        http://www.gophernet.org/projects/redland-php/
 */
class LibRDF_Iterator implements Iterator
{
    /**
     * A cache of whether the iterator is still valid.
     *
     * @var     boolean
     * @access  private
     */
    private $isvalid;

    /**
     * The underlying librdf_iterator resource.
     *
     * @var     resource
     * @access  private
     */
    private $iterator;

    /**
     * An integer used to provide keys over the iteration.
     *
     * There are no keys created by the librdf_iterator data, so iteration
     * keys are created as an integer with an initial value of 0 increasing
     * by one for each call of {@link next}.
     *
     * @var     integer
     * @access  private
     */
    private $key;

    /**
     * A reference to the iterator's source object to prevent it from being
     * garbage collected before the iterator.
     *
     * @var     mixed
     * @access  private
     */
    private $source;

    /**
     * A flag for whether the iterator is rewindable.
     *
     * A iterator may be rewound before {@link next} is called, after which 
     * rewinding invalidates the iterator.
     *
     * @var     boolean
     * @access  private
     */
    private $rewindable;

    /**
     * Create a new iterable object from a librdf_iterator resource.
     *
     * User functions should not create librdf_iterator resources directly,
     * so this constructor is intended only to provide an interface into the
     * iterators returned by librdf functions and called by LibRDF classes.
     *
     * @param   resource    $iterator     The librdf_iterator object to wrap
     * @param   mixed       $source     The object that created the iterator
     * @return  void
     * @access  public
     */
    public function __construct($iterator, $source=NULL)
    {
        $this->iterator = $iterator;
        $this->isvalid = true;
        $this->key = 0;
        $this->source = $source;
        $this->rewindable = true;
    }

    /**
     * Free the iterator's resources.
     *
     * @return  void
     * @access  public
     */
    public function __destruct()
    {
        if ($this->iterator) {
            librdf_free_iterator($this->iterator);
        }
    }

    /**
     * Clone a LibRDF_Iterator object.
     *
     * Cloning a iterator is unsupported, so using the clone operator on a
     * LibRDF_Iterator object will produce an empty iterator.
     *
     * @return  void
     * @access  public
     */
    public function __clone()
    {
        $this->iterator = NULL;
        $this->isvalid = false;
    }

    /**
     * Rewind the iterator.
     *
     * Rewinding is not supported, so this call invalidates the iterator unless
     * the iterator is still at the starting position.
     *
     * @return  void
     * @access  public
     */
    public function rewind()
    {
        if (!($this->rewindable)) {
            $this->isvalid = false;
        }
    }

    /**
     * Return the current node or NULL if the iterator is no longer valid.
     *
     * @return  LibRDF_Statement    The current node on the iterator
     * @access  public
     */
    public function current()
    {
        if (($this->isvalid) and (!librdf_iterator_end($this->iterator))) {
            // the pointer returned is overwritten when the iterator is
            // advanced or closed, so make a copy of the node
            $ret = librdf_iterator_get_object($this->iterator);
            if ($ret) {
                return LibRDF_Node::makeNode(librdf_new_node_from_node($ret));
            } else {
                throw new LibRDF_Error("Unable to get current node");
            }
        } else {
            return NULL;
        }
    }

    /**
     * Return the key of the current element on the iterator.
     *
     * @return  integer     The current key
     * @access  public
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Advance the iterator's position.
     *
     * @return  void
     * @access  public
     */
    public function next()
    {
        if ($this->isvalid) {
            $this->rewindable = false;
            $ret = librdf_iterator_next($this->iterator);
            if ($ret) {
                $this->isvalid = false;
            } else {
                $this->key++;
            }
        }
    }

    /**
     * Return whether the iterator is still valid.
     *
     * @return  boolean     Whether the iterator is still valid
     * @access  public
     */
    public function valid()
    {
        if (($this->isvalid) and (!librdf_iterator_end($this->iterator))) {
            return true;
        } else {
            return false;
        }
    }
}

?>
