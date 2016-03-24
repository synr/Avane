<?php
namespace Avane\Compiler;



class AvaneTag extends \Avane\Avane
{
    protected $avaneNames;
    protected $content;

    function __construct($thisOne)
    {
        if($thisOne) parent::__construct($thisOne);

        if(!isset($this->anaveNames))
            $this->avaneNames = file_exists($this->avNamesPath) ? json_decode(file_get_contents($this->avNamesPath), true)
                                                                : [];
    }




    /**
     * Compile
     *
     * Collects the avane tags, and compile the avane helper files.
     *
     * @return AvaneAvTagCompiler
     */

    function compile($htmlContent)
    {

        if(!$htmlContent)
            return false;

        $this->content = $htmlContent;

        $this->collect()
             ->outputJs();
    }




    /**
     * Collect
     *
     * Parses the html content, and get all the av-groups and av-names,
     * then push them into a file.
     *
     * @return AvaneAvTagCompiler
     */

    function collect()
    {

        \phpQuery::newDocumentHTML($this->content);

        //exit(var_dump(count(pq("*[av-group]"))));



        //$content = str_get_html($this->content);
        $avGroups = pq("*[av-group]");

        /** Search for all the av-groups */
        foreach($avGroups as $element)
        {
            $thisOne = pq($element);


            $group = $thisOne->attr('av-group');

            $avNames = $thisOne->find('*[av-name]');

            /** Push all the elements which are under this group to the list */
            foreach($avNames as $child)
            {
                $thisOne = pq($child);
                $this->pushGroup($group, $thisOne->attr('av-name'));
            }
        }

        /** Includes self when av-name and av-group were in the same tag */
        /*foreach($content->find('*[av-name]') as $element)
        {
            if(!isset($element->attr['av-group']))
                $this->pushGroup('%', $element->attr['av-name']);
            else
                $this->pushGroup($element->attr['av-group'], $element->attr['av-name']);
        }*/

        /** Update the avane names file */
        file_put_contents($this->avNamesPath, json_encode($this->avaneNames));

        return $this;
    }




    /**
     * Push Group
     *
     * Push a name into a group.
     *
     * @return AvaneAvTagCompiler
     */

    function pushGroup($group, $name)
    {
        if(!isset($this->avaneNames[$group]))
            $this->avaneNames[$group] = [];

        array_push($this->avaneNames[$group], $name);
        $this->avaneNames[$group] = array_unique($this->avaneNames[$group]);

        return $this;
    }




    /**
     * Output JS
     *
     * Converts the names to the javascript variables,
     * so we can use it quickly in the javascript.
     *
     * @return AvaneAvTagCompiler
     */

    function outputJs()
    {
        $prefix = $this->avaneTagJsPrefix;
        $js     = 'window.' . $prefix . '_ = {};';
        $js    .= 'function callAv(avName){ return ' . $prefix . '(avName) };';
        $js    .= $prefix . '(document).ready(function(){';

        foreach($this->avaneNames as $group => $nameList)
        {
            foreach($nameList as $name)
            {
                $js .= "window.{$group}_$name = \"[av-group='$group'] *:not([av-group]) [av-name='$name'], [av-group='$group'] > [av-name='$name']\"; \n";
                $js .= "window.{$prefix}_.{$group}_$name = callAv({$group}_$name); \n";
            }
        }

        $js .= '});';


        file_put_contents($this->avScriptPath, $js);

        return $this;
    }




    /**
     * Output Css
     *
     * Now disabled.
     */

    static function outputCss($string)
    {
        return preg_replace('/%%(.*?)%%/', '[av-group="$1"] *:not([av-group]),'."\n".'[av-group="$1"] >', $string);
    }
}
?>