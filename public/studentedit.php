<?php
/* NHS Check-in - A fork of the CSF Check-in service.
Used for check-in, meeting management, and record keeping.
Copyright (C) 2017-2018 Ryan Keegan
	
This program is free software; you can redistribute it and/or modify it
under the terms of the GNU General Public License as published by the
Free Software Foundation; either version 3, or (at your option) any
later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; see the file LICENSE.  If not see
<http://www.gnu.org/licenses/>.  */

    //Enables editing of specific students
    include_once("../admin/database.php");
    loggedIn();
    if(!has_permission()) {
        header("Location: ./students.php");
    }
    $editStudentId = mysqli_real_escape_string($databaseConnect, $_GET['id']);
    
    $statement = $databaseConnect->prepare("SELECT name, student_id, grade, id, cs1, cs2, dues FROM students WHERE id=?");
    $statement->bind_param("s", $editStudentId);
    $statement->execute();
    $row = $statement->get_result()->fetch_assoc();
    
    $editStudentName = $row['name'];
    $editStudentStudentId = $row['student_id'];
    $editStudentGrade = $row['grade'];
    $editStudentId = $row['id'];
    $editDues = $row['dues'];
    $editCS1 = $row['cs1'];
    $editCS2 = $row['cs2'];
    if(has_permission() && isset($_POST['submit'])) {
        $checkFields = array('name', 'student_id', 'grade', 'dues', 'cs1', 'cs2');
        if(!array_diff($checkFields, array_keys($_POST))) {
            $editStudentName = mysqli_real_escape_string($databaseConnect, $_POST['name']);
            $editStudentStudentId = mysqli_real_escape_string($databaseConnect, $_POST['student_id']);
            $editStudentGrade = mysqli_real_escape_string($databaseConnect, $_POST['grade']);
	    $editDues = mysqli_real_escape_string($databaseConnect, $_POST['dues']);
	    $editCS1 = mysqli_real_escape_string($databaseConnect, $_POST['cs1']);
	    $editCS2 = mysqli_real_escape_string($databaseConnect, $_POST['cs2']);
            
            $statement = $databaseConnect->prepare("UPDATE students SET name=?, student_id=?, grade=?, cs1=?, cs2=?, dues=? WHERE id=?");
            $statement->bind_param("sssssss", $editStudentName, $editStudentStudentId, $editStudentGrade, $editCS1, $editCS2, $editDues, $editStudentId);
            $statement->execute();
            
            header("Location: ./students.php");
        }
    }
    ?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="./css/main.css">
        <meta charset="utf-8">
        <title>Edit Student</title>
    </head>
    <body>
        <header>
            <h1>Editing Student: <?php echo $editStudentName ?></h1>
            <?php nav_bar() ?>
        </header>
        <form action="studentedit.php?id=<?php echo $editStudentId ?>" method="post">
            <table>
                <th></th>
                <th></th>
                <tr>
                    <td>Name: </td>
                    <td>
                        <input type='text' name='name' autocomplete='off' value='<?php echo $editStudentName ?>' size='20px'>
                    </td>
                </tr>
                <tr>
                    <td>Student ID: </td>
                    <td>
                        <input type='text' name='student_id' autocomplete='off' value='<?php echo $editStudentStudentId ?>' size='20px'>
                    </td>
                </tr>
                <tr>
                    <td>Grade: </td>
                    <td>
                        <input type='text' name='grade' autocomplete='off' value='<?php echo $editStudentGrade ?>' size='20px'>
                    </td>
                </tr>
                <tr>
                    <td>CS 1: </td>
                    <td>
                        <input type='text' name='cs1' autocomplete='off' value='<?php echo $editCS1 ?>' size='20px'>
                    </td>
                </tr>

		<tr>
		    <td>CS 2: </td>
		    <td>
		        <input type='text' name='cs2' autocomplete='off' value='<?php echo $editCS2 ?>' size='20px'>
		    </td>
		</tr>
		<tr>
		    <td>Dues: </td>
		    <td>
		        <input type='text' name='dues' autocomplete='off' value='<?php echo $editDues ?>' size='20px'>
		    </td>
		</tr>
                <tr>
                    <td><input type="submit" name="submit" value="Submit"></td>
                </tr>
            </table>
        </form>
    </body>
</html>