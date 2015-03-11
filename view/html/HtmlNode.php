<?php
namespace qeywork;

/**
 * Create a html node with the given tag name
 * @param srting $tag
 * @param mixed $content
 * @return \HtmlNode 
 */

class HtmlNode implements IHtmlEntity
{
    protected $tag;
    protected $idAttr;
    protected $attributes;
    protected $selfClosed; // html elements like <br />
    protected $classes;
    protected $children;
    
    public function __construct($tag, $selfClosed = false)
    {
        $this->tag = $tag;
        $this->attributes = array();
        $this->selfClosed = $selfClosed;
        $this->classes = array();
        $this->id = null;
        $this->children = new HtmlEntityList();
    }
    
    /**
     * Add an attrivute to yourt element
     * @param string $mixed
     * @param string $value
     * @return HtmlNode 
     */
    public function attr($mixed, $value = '')
    {
        if ($mixed === 'id')
        {
            $this->id($value);
            return $this;
        }
        
        if (is_object($mixed)) {
            $mixed .= ''; //convert to string if possible
        }
        
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $this->attributes[$key] = $value;
            }
        } else {
            $this->attributes[$mixed] = $value . '';
        }
        return $this;
    }
    
    /**
     * @param string $id Get or set the id of the element
     *      (if null is passed will return the id, otherwise it will set the id and return the object)
     * @return HtmlNode 
     */
    public function id($id)
    {
        $this->idAttr = $id;
        return $this;
    }
    
    /**
     * Same as calling attr('value', $val)
     * @param type $val
     * @return HtmlNode 
     */    
    public function val($val)
    {
        return $this->attr('value', $val);
    }
    
    /**
     * Adds a class (list) to the node
     * @param string $class
     * @return HtmlNode 
     */
    public function cls($class)
    {
        $classes = explode(' ', trim($class));
        foreach ($classes as $class) {
            if (!empty($class)) {
                $this->classes[$class] = $class;
            }
        }
        return $this;
    }
    
    public function clean() {
        $this->children = new HtmlEntityList();
        return $this;
    }
    
    public function text($text) {
        $this->content(new TextNode(htmlspecialchars($text)));
        return $this;
    }
    
    /**
     * @param HtmlEntity $child,... list childrens
     */
    public function content() {
        $this->clean();
        for ($i = 0 ; $i < func_num_args(); $i++) {
            $this->append(func_get_arg($i));
        }
        return $this;
    }
    
    public function htmlContent($html) {
        $this->clean();
        $this->children[] = new HtmlWrapperNode($html);
        return $this;
    }
    
    public function append(IHtmlEntity $item) {
        if ($item == NULL) {
            return $this;
        }
        
        if ($item instanceof HtmlEntityList) {        
            foreach ($item as $item) {
                $this->children[] = $item;
            }
        } else if ($item instanceof IHtmlEntity) {
            $this->children[] = $item;
        } else {
            throw new ArgumentException('$mixed must be an IHtmlEntity, ' . gettype($item) . ' given: ' . var_export($item)); 
        }
            
        return $this;
    }
    
    public function add(IHtmlEntity $item) {
        $this->append($item);
    }
    
    public function __call($func, $attrs) {
        if (empty($attrs)) {
            $attrs[0] = '';
        }
        
        return $this->attr($func, $attrs[0]);
    }
    
    /**
     * Clones this object, keeps the ability to chain,
     * by using the clone keyword internally
     * @return HtmlNode 
     */
    public function cloneNode() {
        return clone $this;
    }
    
    public function render() {
        return $this;
    }
    
    public function toString() {    
        $html = '<' . $this->tag;
        
        if ($this->idAttr !== null) {
            $html .= ' id="' . $this->idAttr . '"';
        }
        
        if (! empty($this->classes)) {
            $html .= ' class="' . implode(' ', $this->classes) . '"';
        }
            
        foreach ($this->attributes as $key => $value) {
            $html .= ' ' . $key;
            if (! empty($value)) {
                $html .= '="' . $value . '"';
            }
        }
        
        if ($this->selfClosed) {
            $html .= '/>';
        } else {
            $html .= '>' . $this->children . '</' . $this->tag . '>';
        }
        
        return $html;
    }
    
    public function __toString() {
        return $this->toString();
    }
}
