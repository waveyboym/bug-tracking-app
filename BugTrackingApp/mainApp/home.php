<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Dongle:wght@700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/d271141ba3.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="homeStyles.css">
    <title>Bug Tracking App</title>
</head>
<body>
    <div class="header-section">
        <div class="Apptitle">
            <h1>Bug Tracking App</h1>
            <div class="bugIcon">
                <ion-icon name="bug-outline"></ion-icon>
            </div>
        </div>
        <div class="darkMode">
            <div class="darkModeIcon" title="click to switch between other themes">
                <ion-icon name="contrast-outline" onclick="switchThemes()"></ion-icon>
            </div>
        </div>
        <div class="logOut">
            <a href="../signuplogin/logoutFile.php" class="logOutIcon">
                <ion-icon name="log-out-outline"></ion-icon>
            </a>
        </div>
        <div class="userProfile">
            <div class="userIcon">
                <ion-icon name="person-circle-outline"></ion-icon>
            </div>
            <h1><?php echo $_SESSION["LoggedUserid"];?></h1>
        </div>
    </div>

    <div class="main-section">
        <div class="cardBox">
            <h1>At a glance...</h1>
            <div class="card">
                <ion-icon name="stats-chart-outline"></ion-icon>
                <div class="bugCount">
                    <h2>0</h2>
                    <h3>bugs have been logged</h3>
                </div>
            </div>
    
            <div class="card">
                <ion-icon name="checkmark-done-outline"></ion-icon>
                <div class="bugRatio">
                    <h2>0%</h2>
                    <h3>are resolved</h3>
                </div>
                <div class="bar-area">
                    <div class="complete-bar">
                        <div class="bar-to-fill">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content-section">
            <div class="bug-details">
                <h1>Bugs</h1>
                <h2>Bug description</h2>
                <h3>status</h3>
                <h4>actions</h4>     
                <h5>date added</h5>
            </div>
            <div class="addNewBugs">
                <h1>Add a new bug: </h1>
                    <textarea  id="bugDescriptionText" name="actualDescription">enter description</textarea>
                    <br>
                    <input type="text" id="bugStatusText" name="status" placeholder="unresolved/resolved?">
                    <br>
                    <button type="submit" name="submit" onclick="createNewBug()">Create New</button>
            </div>
        </div>
    </div>
    
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>



















<!-- javascript code for styling page-->
<script>
//global values
let globalThemeVarCounter = 0;
let globalNumOfBugs = <?php echo $_SESSION["BugCount"]; ?>;//will change depending on database
let globalNumOfBugsPercentage = <?php echo $_SESSION["NumOfResolvedBugs"]; ?>;//will change depending on database
let globalIdOfUser = <?php echo $_SESSION["LoggedidNum"];?>
<?php $bugReportArray =  json_decode($_SESSION["BugReport"]); ?>;

//onload values for counting interval functions !!!do not modify!!!
let counterForTextOutput = 1;
let counterForPercentageText = 1;
let counterForBarFill = 1;

//interval functions do not modify !!!
const globalIntervalBugCounting = setInterval(displayBugCounting, 500);
const globalPercentageCounting = setInterval(displayPercentageCounting, 50);
const globalBarFill = setInterval(displayBarFill, 50);

//functions that run onload of page

function displayBugCounting(){
    let getBigBugCounter = document.querySelector(".bugCount h2");
    if(counterForTextOutput <= globalNumOfBugs){
        if(getBigBugCounter == null){
            return;
        }
        getBigBugCounter.innerHTML = counterForTextOutput;
        ++counterForTextOutput;
    }
    else if(counterForTextOutput >= globalNumOfBugs){
        stopBugCounterIntervalFunction();
    }
}

function displayPercentageCounting(){
    let getPercentage = document.querySelector(".bugRatio h2");
    if(counterForPercentageText <= globalNumOfBugsPercentage){
        if(getPercentage == null){
            return;
        }
        getPercentage.innerHTML = counterForPercentageText + "%";
        ++counterForPercentageText;
    }
    else if(counterForPercentageText >= globalNumOfBugsPercentage){
        stopPercentageIntervalFunction();
    }
}

function displayBarFill(){
    let getBar = document.querySelector(".bar-to-fill");
    if(counterForBarFill <= globalNumOfBugsPercentage){
        if(getBar == null){
            return;
        }
        getBar.style.width = counterForBarFill + "%";
        ++counterForBarFill;
    }
    else if(counterForBarFill >= globalNumOfBugsPercentage){
        if(globalNumOfBugsPercentage == 100){
            getBar.style.borderBottomRightRadius = "10px";
            getBar.style.borderTopRightRadius = "10px";
        }
        else if(globalNumOfBugsPercentage < 100){
            getBar.style.borderBottomRightRadius = "0px";
            getBar.style.borderTopRightRadius = "0px";
        }
        stopBarIntervalFunction();
    }
}

function stopBugCounterIntervalFunction(){
    clearInterval(globalIntervalBugCounting);
}

function stopPercentageIntervalFunction(){
    clearInterval(globalPercentageCounting);
}

function stopBarIntervalFunction(){
    clearInterval(globalBarFill);
}

let readPHParrayValues = function(){
    <?php
    for($i = 0; $i < count($bugReportArray); ++$i){
        $BugDescription = $bugReportArray[$i]->Bugdesc;
        $Status = $bugReportArray[$i]->BugStatus;
        $DateLogged = $bugReportArray[$i]->BugDateLogged;
        ?>
        placeNewBugOnDisplay("<?php echo $BugDescription ?>", "<?php echo $Status ?>", "<?php echo $DateLogged ?>");
        <?php
    }
    ?>
}

//for toggling colours
window.onload = function(){
    let savedValue = window.localStorage.getItem("lastSavedStatus");
    if(savedValue >= 0 && savedValue <= 5){
        if(savedValue != 0){
            globalThemeVarCounter = savedValue - 1;
        }
        else if(savedValue == 0){
            globalThemeVarCounter = 5;
        }
        switchThemes();
    }
    readPHParrayValues();
    changeColorOfActions();
}






//main functions that are used often

let switchThemes = function(){

    if(globalThemeVarCounter == 0){
        //blue colour
        document.documentElement.style.setProperty('--blue', 'rgb(3, 118, 181)');
        document.documentElement.style.setProperty('--backgroundWhite', '#303059');
        document.documentElement.style.setProperty('--white', 'rgb(67, 80, 146)');
        document.documentElement.style.setProperty('--textboxColours', 'rgb(64, 101, 150)');
        document.documentElement.style.setProperty('--black', 'rgb(228, 228, 228)');
        document.documentElement.style.setProperty('--cardBoxShadow', '0 0px 15px rgba(0, 0, 0, 0.631)');
        document.documentElement.style.setProperty('--completeBarShadow', '0 7px 25px rgba(0, 0, 0, 0.761)');
        document.documentElement.style.setProperty('--bugDetailsShadow', '0 7px 25px rgba(0, 0, 0, 0.768)');
        document.documentElement.style.setProperty('--bugBottomBorder', '2px solid rgba(255, 255, 255, 0.213)');
        document.documentElement.style.setProperty('--addNewBugTextBoxShadow', '0 5px 15px rgba(0, 0, 0, 0.611)');
        ++globalThemeVarCounter;
    }
    else if(globalThemeVarCounter == 1){
        //pink colour
        document.documentElement.style.setProperty('--blue', 'rgb(137, 3, 181)');
        document.documentElement.style.setProperty('--backgroundWhite', '#593059');
        document.documentElement.style.setProperty('--white', 'rgb(146, 67, 140)');
        document.documentElement.style.setProperty('--textboxColours', 'rgb(146, 64, 150)');
        document.documentElement.style.setProperty('--black', 'rgb(228, 228, 228)');
        document.documentElement.style.setProperty('--cardBoxShadow', '0 0px 15px rgba(0, 0, 0, 0.631)');
        document.documentElement.style.setProperty('--completeBarShadow', '0 7px 25px rgba(0, 0, 0, 0.761)');
        document.documentElement.style.setProperty('--bugDetailsShadow', '0 7px 25px rgba(0, 0, 0, 0.768)');
        document.documentElement.style.setProperty('--bugBottomBorder', '2px solid rgba(255, 255, 255, 0.213)');
        document.documentElement.style.setProperty('--addNewBugTextBoxShadow', '0 5px 15px rgba(0, 0, 0, 0.611)');
        ++globalThemeVarCounter;
    }
    else if(globalThemeVarCounter == 2){
        //olive green colour
        document.documentElement.style.setProperty('--blue', 'rgb(3, 181, 113)');
        document.documentElement.style.setProperty('--backgroundWhite', '#355930');
        document.documentElement.style.setProperty('--white', 'rgb(122, 146, 67)');
        document.documentElement.style.setProperty('--textboxColours', 'rgb(107, 150, 64)');
        document.documentElement.style.setProperty('--black', 'rgb(228, 228, 228)');
        document.documentElement.style.setProperty('--cardBoxShadow', '0 0px 15px rgba(0, 0, 0, 0.631)');
        document.documentElement.style.setProperty('--completeBarShadow', '0 7px 25px rgba(0, 0, 0, 0.761)');
        document.documentElement.style.setProperty('--bugDetailsShadow', '0 7px 25px rgba(0, 0, 0, 0.768)');
        document.documentElement.style.setProperty('--bugBottomBorder', '2px solid rgba(255, 255, 255, 0.213)');
        document.documentElement.style.setProperty('--addNewBugTextBoxShadow', '0 5px 15px rgba(0, 0, 0, 0.611)');
        ++globalThemeVarCounter;
    }
    else if(globalThemeVarCounter == 3){
        //grey colour
        document.documentElement.style.setProperty('--blue', 'rgb(3, 118, 181)');
        document.documentElement.style.setProperty('--backgroundWhite', '#404640');
        document.documentElement.style.setProperty('--white', 'rgb(115, 118, 110)');
        document.documentElement.style.setProperty('--textboxColours', 'rgb(148, 153, 144)');
        document.documentElement.style.setProperty('--black', 'rgb(228, 228, 228)');
        document.documentElement.style.setProperty('--cardBoxShadow', '0 0px 15px rgba(0, 0, 0, 0.631)');
        document.documentElement.style.setProperty('--completeBarShadow', '0 7px 25px rgba(0, 0, 0, 0.761)');
        document.documentElement.style.setProperty('--bugDetailsShadow', '0 7px 25px rgba(0, 0, 0, 0.768)');
        document.documentElement.style.setProperty('--bugBottomBorder', '2px solid rgba(255, 255, 255, 0.213)');
        document.documentElement.style.setProperty('--addNewBugTextBoxShadow', '0 5px 15px rgba(0, 0, 0, 0.611)');
        ++globalThemeVarCounter;
    }
    else if(globalThemeVarCounter == 4){
        //dark black colour
        document.documentElement.style.setProperty('--blue', 'rgb(3, 118, 181)');
        document.documentElement.style.setProperty('--backgroundWhite', '#111111');
        document.documentElement.style.setProperty('--white', 'rgb(29, 29, 29)');
        document.documentElement.style.setProperty('--textboxColours', 'rgb(53, 53, 53)');
        document.documentElement.style.setProperty('--black', 'rgb(228, 228, 228)');
        document.documentElement.style.setProperty('--cardBoxShadow', '0 0px 15px rgba(0, 0, 0, 0.631)');
        document.documentElement.style.setProperty('--completeBarShadow', '0 7px 25px rgba(0, 0, 0, 0.761)');
        document.documentElement.style.setProperty('--bugDetailsShadow', '0 7px 25px rgba(0, 0, 0, 0.768)');
        document.documentElement.style.setProperty('--bugBottomBorder', '2px solid rgba(255, 255, 255, 0.213)');
        document.documentElement.style.setProperty('--addNewBugTextBoxShadow', '0 5px 15px rgba(0, 0, 0, 0.611)');
        ++globalThemeVarCounter;
    }
    else if(globalThemeVarCounter == 5){
        //returning back to white
        document.documentElement.style.setProperty('--blue', 'rgb(60, 187, 255)');
        document.documentElement.style.setProperty('--backgroundWhite', 'white');
        document.documentElement.style.setProperty('--white', 'white');
        document.documentElement.style.setProperty('--textboxColours', 'white');
        document.documentElement.style.setProperty('--black', 'rgb(0, 0, 0)');
        document.documentElement.style.setProperty('--cardBoxShadow', '0 7px 25px rgba(0, 0, 0, 0.213)');
        document.documentElement.style.setProperty('--completeBarShadow', '0 7px 25px rgba(0, 0, 0, 0.378)');
        document.documentElement.style.setProperty('--bugDetailsShadow', '0 7px 25px rgba(0, 0, 0, 0.323)');
        document.documentElement.style.setProperty('--bugBottomBorder', '2px solid rgba(0, 0, 0, 0.213)');
        document.documentElement.style.setProperty('--addNewBugTextBoxShadow', '0 5px 15px rgba(0, 0, 0, 0.296)');
        globalThemeVarCounter = 0;
    }

    window.localStorage.setItem("lastSavedStatus", globalThemeVarCounter);
}

let changeColorOfActions = function(){
    let storeStatusClass = document.querySelectorAll(".bugStatus p");
    let storeActionsClass = document.querySelectorAll(".bugActions p");

    for(let i = 0; i < storeStatusClass.length; ++i){
        if(storeStatusClass[i].innerHTML == "unresolved"){
            storeActionsClass[i].style.backgroundColor= "green";
        }
        else if(storeStatusClass[i].innerHTML == "resolved"){
            storeActionsClass[i].style.backgroundColor= "red";
        }
    }
}

let placeNewBugOnDisplay = function(bugDescription, bugStatus, dateLogged){
    let classToAddTo = document.querySelector(".bug-details h5");
    let newLoggedBug = document.createElement("div");
    newLoggedBug.className = "tempholder";
    let onclickEventText = "'"+ bugDescription + "'";
    let actionToPlace;

    if(bugStatus == "resolved"){
        actionToPlace = "delete";
    }
    else if(bugStatus != "resolved"){
        actionToPlace = "complete";
    }

    newLoggedBug.innerHTML = '<div class="bug">'
                                +'<div class="bugDescription">'
                                    + '<p>'+ bugDescription +'</p>'
                                +'</div>'
                                +'<div class="bugStatus">'
                                    +'<p>'+ bugStatus +'</p>'
                                +'</div>'
                                +'<div class="bugActions" onclick="bugActions('+ onclickEventText +')">'
                                    +'<p>'+ actionToPlace +'</p>'
                                +'</div>'
                                +'<div class="bugDateTime">'
                                    +'<p>'+ dateLogged +'</p>'
                                +'</div>'
                            +'</div>';
    classToAddTo.insertAdjacentHTML("afterend", newLoggedBug.innerHTML);
}

let updateDisplayNumbers = function (){
    let getBigGlobalNum = document.querySelector(".bugCount h2");
    let getPercentageNum = document.querySelector(".bugRatio h2");
    let getPercentageBar = document.querySelector(".bar-to-fill");
    let getBugStatus = document.querySelectorAll(".bugStatus p");
    let numOfResolved = 0;

    for(let i = 0; i < getBugStatus.length; ++i){
        if(getBugStatus[i].innerHTML == "resolved"){
            ++numOfResolved;
        }
    }

    if(globalNumOfBugs == 0){
        globalNumOfBugsPercentage = 0;
    }
    else if(globalNumOfBugs > 0){
        globalNumOfBugsPercentage = (numOfResolved/globalNumOfBugs)*100;
    }

    getBigGlobalNum.innerHTML = globalNumOfBugs;
    getPercentageNum.innerHTML = globalNumOfBugsPercentage + "%";
    if(globalNumOfBugsPercentage == 100){
        getPercentageBar.style.width = globalNumOfBugsPercentage + "%";
        getPercentageBar.style.borderBottomRightRadius = "10px";
        getPercentageBar.style.borderTopRightRadius = "10px";
    }
    else if(globalNumOfBugsPercentage < 100){
        getPercentageBar.style.width = globalNumOfBugsPercentage + "%";
        getPercentageBar.style.borderBottomRightRadius = "0px";
        getPercentageBar.style.borderTopRightRadius = "0px";
    }
}

//add new bug plus reflect changes on database and array
let createNewBug = function(){
    let getBugDescriptionText = document.getElementById("bugDescriptionText").value;
    let getBugStatusText = document.getElementById("bugStatusText").value;

    if(getBugDescriptionText == ""){
        alert("Please enter a proper description");
        return;
    }
    if(getBugStatusText == ""){
        alert("Please enter a proper status");
        return;
    }
    if(getBugStatusText != "resolved" && getBugStatusText != "unresolved"){
        alert("Please enter resolved or unresolved as the status (case sensitive)");
        return;
    }

    //getting current time and date
    let now = new Date();
    let year = now.getFullYear();
    let month = now.getMonth()+1; 
    let day = now.getDate();
    let hour = now.getHours();
    let minute = now.getMinutes();
    let second = now.getSeconds();
    if(month.toString().length == 1) {
        month = '0'+ month;
    }
    if(day.toString().length == 1) {
        day = '0'+ day;
    }   
    if(hour.toString().length == 1) {
        hour = '0'+ hour;
    }
    if(minute.toString().length == 1) {
        minute = '0'+ minute;
    }
    if(second.toString().length == 1) {
        second = '0'+ second;
    }      
    let bugDateTimeLogged = year + "-" + month + "-" + day + " " + hour + ":" + minute+ ":" + second;
    placeNewBugOnDisplay(getBugDescriptionText, getBugStatusText, bugDateTimeLogged);
    changeColorOfActions();
    ++globalNumOfBugs;
    updateDisplayNumbers();

    //go to php file using ajax and put in database
    let bugReportVariable = "desc=" + getBugDescriptionText + "&status=" + getBugStatusText + "&datelogged=" + bugDateTimeLogged;

    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onload = function (){
        console.log(this.responseText);
    };

    xmlhttp.open("POST", "controller.php");
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(bugReportVariable);
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    document.getElementById("bugDescriptionText").value = "enter description";
    document.getElementById("bugStatusText").value = "";
}

//delete or set bug status to resolved plus reflect changes on database and array
let bugActions = function(descriptionOfBugToSearch){
    let getBugDescriptionClasses = document.querySelectorAll(".bugDescription p");

    for(let i = 0; i < getBugDescriptionClasses.length; ++i){
        if(getBugDescriptionClasses[i].innerHTML == descriptionOfBugToSearch){
            let getBugActionsClasses = document.querySelectorAll(".bugActions p");
            //therefore found description
            if(getBugActionsClasses[i].innerHTML == "complete"){
                // set to complete on database and change to delete on page
                let getBugStatusClasses = document.querySelectorAll(".bugStatus p");
                getBugActionsClasses[i].innerHTML = "delete";
                getBugStatusClasses[i].innerHTML = "resolved";
                changeColorOfActions();

                //go to php file using ajax and put in database
                let bugReportVariable = "desc=" + descriptionOfBugToSearch + "&status=unresolved";

                const xmlhttp = new XMLHttpRequest();
                xmlhttp.onload = function (){
                    console.log(this.responseText);
                };

                xmlhttp.open("POST", "controller.php");
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send(bugReportVariable);
                break;
            }
            else if(getBugActionsClasses[i].innerHTML == "delete"){
                //delete bug from database and update page
                let getBug = document.querySelectorAll(".bug");
                getBug[i].remove();
                
                --globalNumOfBugs;

                //go to php file using ajax and put in database
                let bugReportVariable = "desc=" + descriptionOfBugToSearch + "&status=resolved";

                const xmlhttp = new XMLHttpRequest();
                xmlhttp.onload = function (){
                    console.log(this.responseText);
                };

                xmlhttp.open("POST", "controller.php");
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send(bugReportVariable);
                break;
            }
        }
    }
    
    updateDisplayNumbers();
}
</script>