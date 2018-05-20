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

    if(has_permission() && isset($_POST['submit'])) {
        $checkFields = array('name', 'student_id', 'grade', 'dues', 'cs1', 'cs2', 'status');
        if(!array_diff($checkFields, array_keys($_POST))) {
            $editStudentName = mysqli_real_escape_string($databaseConnect, $_POST['name']);
            $editStudentStudentId = mysqli_real_escape_string($databaseConnect, $_POST['student_id']);
            $editStudentGrade = mysqli_real_escape_string($databaseConnect, $_POST['grade']);
	    $editDues = mysqli_real_escape_string($databaseConnect, $_POST['dues']);
	    $editCS1 = mysqli_real_escape_string($databaseConnect, $_POST['cs1']);
	    $editCS2 = mysqli_real_escape_string($databaseConnect, $_POST['cs2']);
	    $editStatus = mysqli_real_escape_string($databaseConnect, $_POST['status']);
            
            $statement = $databaseConnect->prepare("INSERT INTO students (name, student_id, grade, cs1, cs2, dues, membership) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $statement->bind_param("sssssss", $editStudentName, $editStudentStudentId, $editStudentGrade, $editCS1, $editCS2, $editDues, $editStatus);
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
        <title>Add Student</title>
    </head>
    <body>
        <header>
            <h1>Add Student</h1>
            <?php nav_bar() ?>
        </header>
        <form action="studentadd.php" method="post">
            <table>
                <th></th>
                <th></th>
                <tr>
                    <td>Name: </td>
                    <td>
                        <input type='text' name='name' autocomplete='off' size='20px'>
                    </td>
                </tr>
                <tr>
                    <td>Student ID: </td>
                    <td>
                        <input type='text' name='student_id' autocomplete='off' size='20px'>
                    </td>
                </tr>
                <tr>
                    <td>Grade: </td>
                    <td>
                        <input type='text' name='grade' autocomplete='off' size='20px'>
                    </td>
                </tr>
		<tr>
		    <td>Status: </td>
		    <td>
		        <input type='text' name='status' autocomplete='off' placeholder='Inductee/Full' size='20px'>
		    </td>
		</tr>
                <tr>
                    <td>CS 1: </td>
                    <td>
                        <input type='text' name='cs1' autocomplete='off' placeholder='Y/N' size='20px'>
                    </td>
                </tr>
		<tr>
		    <td>CS 2: </td>
		    <td>
		        <input type='text' name='cs2' autocomplete='off' placeholder='Y/N' size='20px'>
		    </td>
		</tr>
		<tr>
		    <td>Dues: </td>
		    <td>
		        <input type='text' name='dues' autocomplete='off' placeholder='Y/N' size='20px'>
		    </td>
		</tr>
                <tr>
                    <td><input type="submit" name="submit" value="Submit"></td>
                </tr>
            </table>
        </form>
    </body>
</html>