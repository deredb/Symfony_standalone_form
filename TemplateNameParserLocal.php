<?php

/*
 * Credits
 *
 * For most part the code is taken from Symfony Templating component, and I have added
 * snippets of code to fit my project needs 
 * https://github.com/symfony/Templating/blob/master/TemplateNameParser.php).
 * I acknowledges the work of the original author
 * 
 * Copyright (c) 2004-2015 Fabien Potencier
 * 
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 */
 

use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Component\Templating\TemplateReferenceInterface;
use Symfony\Component\Templating\TemplateReference;


/**
 * TemplateNameParserLocal is project implementation of TemplateNameParserInterface.
 *
 * This implementation takes everything as the template name
 * and the extension for the engine.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Dere I added some code to fit my project
 * @api
 */
class TemplateNameParserLocal implements TemplateNameParserInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function parse($name)
    {
        if ($name instanceof TemplateReferenceInterface) {
            
            return $name;
            
        }
        
        /***start of the code I added ***/
        $formatedPath = str_replace(':', ':', $name, $count);


        if ($count >=2 ) {
             $pathList = explode(':', $name);
             $formatedPath = '';


           for($i=0; $i < sizeOf($pathList); $i++) {
                if($i==0){
                    $formatedPath = $pathList[$i].':';   
                } else {

                    if($i!==sizeOf($pathList)-1) {

                        $formatedPath .= $pathList[$i].'/';

                    } else {

                        $formatedPath .= $pathList[$i];

                   }
               }

           }

         } else {
            $formatedPath = str_replace(':', '/', $formatedPath);
         }
         /***end of part of the code I added**/
        $engine = null;
        if (false !== $pos = strrpos($formatedPath, '.')) {
            $engine = substr($formatedPath, $pos + 1);
        }

        return new TemplateReference($formatedPath, $engine);
    }
}
