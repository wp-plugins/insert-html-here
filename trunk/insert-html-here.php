<?php
/*
Plugin Name: Insert HTML Here
Plugin URI: http://programphases.com/?page_id=1701
Description: Plugin to insert html stored in an option into a post or page.
Version: 1.0
Author: Program Phases, A Step Beyond Hello World!
Author URI: http://programphases.com

Copyright (C) <2008>  <Program Phases LLC>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

/*
   The DoInsertHTMLHere function  is called when the wordpress shortcode [inserthtmlhere x] is evaluated.
   The first entry in the $taContent parameter array is the parameter specified in the shortcode.
*/
function DoInsertHTMLHere($taContent)
{
   $lcCurrentHTML = null;

   $lcCurrentHTML = get_option('gcInsertHTMLHereOption' . $taContent[0]);
   $lcCurrentHTML = str_replace("\\", "", $lcCurrentHTML);

   return $lcCurrentHTML;
}


/*
   The add_shortcode() function creates the shortcode and associates the inserthtmlhere name with with DoInsertHTMLHere function.   
*/
add_shortcode('insert-html-here', 'DoInsertHTMLHere');


/*
   The modify_menu() function associates the AdminInsertHTMLHereOptions function to the Wordpress Settings page
   when called with the add_action() function.
*/
function InsertHTMLHere_modify_menu() {
        add_options_page(
                'InsertHTMLHere',
                'InsertHTMLHere',
                'manage_options',
                InsertHTMLHere,
                'AdminInsertHTMLHereOptions'
        );
}
add_action('admin_menu','InsertHTMLHere_modify_menu');


/*
   The AdminInsertHTMLHereOptions() function is the option page for the plugin settings.
   This function will save the options if the submit button has been pressed.
   This function always retrives the current option values.
*/
function AdminInsertHTMLHereOptions()
{
   Print("<h2>InsertHTMLHere Plugin</h2>");

   if ($_REQUEST['submit'])
   {
        SaveInsertHTMLHereOptions();
   }

   InsertHTMLHereAdminPage();
}

/*
   The SaveInsertHTMLHereOptions() function loops through all of the current options
   and saves each one.
*/
function SaveInsertHTMLHereOptions()
{
   $i = null;
   $lnInsertHTMLHereOptionCount = null;
   $lcCurrentHTML = null;


   if ($_REQUEST['gnInsertHTMLHereOptionCount']) 
   {
      $lnInsertHTMLHereOptionCount = $_REQUEST['gnInsertHTMLHereOptionCount'];
      update_option('gnInsertHTMLHereOptionCount',$lnInsertHTMLHereOptionCount);
   }
   else
   {
      $lnInsertHTMLHereOptionCount = get_option('gnInsertHTMLHereOptionCount');
   }

   for ($i=1;$i<=$lnInsertHTMLHereOptionCount;$i++)
   {
      if ($_REQUEST['gcInsertHTMLHereOption' . $i]) 
      {
          $lcCurrentHTML = $_REQUEST['gcInsertHTMLHereOption' . $i];
          $lcCurrentHTML = str_replace("\\", "", $lcCurrentHTML);
          $lcCurrentHTML = str_replace("[", "", $lcCurrentHTML);
          $lcCurrentHTML = str_replace("]", "", $lcCurrentHTML);

          update_option('gcInsertHTMLHereOption' . $i,$lcCurrentHTML);
      }
   }
}


/*
   The InsertHTMLHereAdminPage() function allows each option to be edited and saved.
*/
function InsertHTMLHereAdminPage()
{
   $i = null;
   $lnInsertHTMLHereOptionCount = null;
   $lcCurrentHTML = null;

   $lnInsertHTMLHereOptionCount = get_option('gnInsertHTMLHereOptionCount');

   if ($lnInsertHTMLHereOptionCount == null)
   {
      $lnInsertHTMLHereOptionCount = 1;
   }

   // Build the form
   Print ("<form method='post'>");

    // Build the combo box
   Print("<table border='0'><tr><td> </td></tr><tr><td width=10></td><td><strong>InsertHTMLHere Option Count</strong></td><td><select size='1' name='gnInsertHTMLHereOptionCount' ID='oComboBox1'>");

   // Add the combo box entries
   for ($i=1;$i<=30;$i++)
   {
      if ($i == $lnInsertHTMLHereOptionCount)
      {
         // The current value of the combo box is set to the value of the $lnInsertHTMLHereOptionCount
         Print("<option value='" . $i . "' selected>" . $i . "</option>");
      }
      else
      {
         Print("<option value='" . $i . "'>" . $i . "</option>");
      }
   }

   // End building the combo box
   Print("</select></td></tr><tr><td> </td></tr></table>");

   Print("<table border='0'>");

   // Create the textarea objects for each option.
   for ($i=1;$i<=$lnInsertHTMLHereOptionCount;$i++)
   {
          $lcCurrentHTML = get_option('gcInsertHTMLHereOption' . $i);
          $lcCurrentHTML = str_replace("\\", "", $lcCurrentHTML);
          $lcCurrentHTML = str_replace("[", "", $lcCurrentHTML);
          $lcCurrentHTML = str_replace("]", "", $lcCurrentHTML);
       
          Print("<tr><td width=10></td><td><label align='top' for='gcInsertHTMLHereOption" . $i . "'>" . "<strong>Option" . $i . ":</strong>" . "<br><textarea cols=50 rows=10 name=" . "'" . "gcInsertHTMLHereOption" . $i . "'>" . $lcCurrentHTML . "</textarea></label><br /></td></tr><tr><td width=10></td></tr>");
   }

   Print("</table>");
   Print ("<input type='submit' name='submit' value='Save' ></form>");
}
?>
