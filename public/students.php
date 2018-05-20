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

    //Overview for Students
    include_once("../admin/database.php");
    loggedIn();
    ?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="./css/main.css">
        <link rel="stylesheet" href="./css/table.css">
        <meta charset="utf-8">
        <title>Students</title>
    </head>
    <body>
        <header>
            <h1>Students Overview</h1>
            <?php nav_bar() ?>
        </header>
        <div>
            <form action="attendance.php" method="post" style="display: inline;">
                Student ID: <input type="text" name="query" size="20px">
                <input type="submit" name="submit" value="Search">
            </form>
            <?php if(has_permission()) : ?>
                <form action="studentadd.php" method="post" style="display: inline;">
                    <input name="submit" class="header-button" type="submit" value="New Student" style="float: right;">
                </form>
            <?php endif; ?>
        </div>
        <br>
        <table align='center'>
            <tr align='center'>
                <th>Name</th>
                <th>Student ID</th>
                <th>Grade Level</th>
                <th>Status</th>
                <th>Dues</th>
                <th>C.S. 1</th>
		<th>C.S. 2</th>
		<th>Attendance</th>
		<th></th>
            </tr>
            <?php
                $result = mysqli_query($databaseConnect, "SELECT student_id, name, grade, id, membership, dues, cs1, cs2 FROM students ORDER BY grade ASC, name ASC");
                while($row = mysqli_fetch_array($result)) {
                    $studentStudentId = $row['0'];
                    $studentName = $row['1'];
                    $studentGrade = $row['2'];
                    $studentId = $row['3'];
		    $studentMembership = $row['4'];
                    $studentDues = $row['5'];
                    $studentCS1 = $row['6'];
                    $studentCS2 = $row['7'];
		    $absences = 0;
		    $resultMeetings = mysqli_query($databaseConnect, "SELECT id, date FROM meetings");
		    while($rowMeetings = mysqli_fetch_array($resultMeetings)) {
		        $attendance = attendanceStudent($studentStudentId, $rowMeetings['0'], $databaseConnect);
		        if($attendance == "#C24040") {
		            $absences++;
		        }
		    }
                    echo "
                        <tr>
                            <td style='color:" , ($absences >= 3 ? " red" : " black") , "'>" . $studentName . "</td>
                            <td>" . $studentStudentId . "</td>
                            <td>" . $studentGrade . "</td>
                            <td>" . $studentMembership . "</td>
			    <td>" . $studentDues . "</td>
			    <td>" . $studentCS1 . "</td>
			    <td>" . $studentCS2 . "</td>
			    <td>";
                            //For each Meeting
                            $resultMeetings = mysqli_query($databaseConnect, "SELECT id, date FROM meetings");
                            while($rowMeetings = mysqli_fetch_array($resultMeetings)) {
                                $attendance = attendanceStudent($studentStudentId, $rowMeetings['0'], $databaseConnect);
                                echo "<a href='meetingedit.php?id=" . $rowMeetings['0'] . "' style='padding-right: 10px; color:" . $attendance . "'>" . $rowMeetings['1'] . "</a> ";
                            }
                            echo "</td>";
                            if(has_permission()) {
                                echo "<td><a href='studentedit.php?id=" . $studentId . "'>Edit</a></td>
                                    </tr>
                                ";
                            }
                }
                ?>
        </table>
    </body>
</html>
