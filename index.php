
<!DOCTYPE html>
<html>
  <head>
    <title>Welcome to E-JobFinder: One-stop-shop for employment</title>
    <?php include_once "header.php"; ?>
  </head>
  <body>
      <nav class="navbar navbar-inverse navbar-fixed-top">
       <div class="container-fluid">
         <div class="navbar-header">
           <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
           <a class="navbar-brand" href="index.php"><img src="images/e-jobFinder.png" alt="Logo"/></a>
         </div>
          <div class="collapse navbar-collapse" id="myNavbar">
         <ul class="nav navbar-nav navbar-right">
           <li class="active"><a href="index.php">Welcome to EJobFinder</a></li>
           <li><a href="signup.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
           <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
           <li><a href="admin/"><span class="glyphicon glyphicon-log-in"></span> Admin CPanel</a></li>
         </ul>
       </div>
        </div>
     </nav>
    <!--Main Content-->
    <div class="container">
     <div class="menu-spacer"></div>
      <div class="row slider"><!--Image Slider for the site-->
        <div class="col-md-12">
            <?php include_once'slider.php'; ?>
        </div>
      </div><!--End of Slider-->
      <!--Company Vision and Mission Statement-->
    <div class="content-fluid mission">
      <div class="h1 title"><a href="#showdata" data-toggle="collapse" >Our Dream</a></div>
      <blockquote cite="http://">
        <p id="showdata" align="justify">
      <i class="fa fa-quote-left fa-3x fa-pull-left fa-border" aria-hidden="true"></i>Our vision is to provide a user-centric, personalized, flexible and adaptive experience with services that are suited for Knowledge workers.
			With a structured platform, we seek to connect prospective employers with potential employees who have the requisite skills.
			Information on job seekers and employers are readily available in a database that is fully searchable and results are presented
			in an intuitive User Interface that matches the status quo of UI design patterns.<i class="fa fa-quote-right fa-3x fa-pull-right fa-border" aria-hidden="true"></i>

        </p>
      </blockquote>
    </div>
      <div class="h1 title"><a href="#showPartner" data-toggle="collapse"> Our partners</a></div>
      <div class="row content-fluid" id="showPartner"><!--Top Companies working with the site-->
        <div class="row company">
          <div class="col-sm-2">
            <img src="images/ehealth.png"/>
              <a id="companyLink" href="http://www.ehealthafrica.org/" target="_blank"> ehealth Africa </a>
          </div>
          <div class="col-sm-2">
            <img src="images/save_the_children.png"/>
              <a id="companyLink" href="http://www.savegirl.org/donation/‎" target="_blank">Save the children</a>
          </div>
          <div class="col-sm-2">
            <img src="images/slf.png"/>
              <a id="companyLink" href="http://www.schoolingforlife.net/" target="_blank">Schooling For Life</a>
          </div>
          <div class="col-sm-2">
            <img src="images/who.png"/>
              <a id="companyLink" href="http://www.who.int/" target="_blank">Wordl Health Organization</a>
          </div>
          <div class="col-sm-2">
            <img src="images/unimak_logo.png"/>
              <a id="companyLink" href="http://www.unimak.edu.sl/" target="_blank"> University Of Makeni</a>
          </div>
          <div class="col-sm-2">
            <img src="images/bracworld.jpg"/>
              <a id="companyLink" href=" http://www.brac.net/" target="_blank">Brac</a>
          </div>
        </div><!--End of row 1-->
        <div class="row company">
          <div class="col-sm-2">
            <img src="images/adax.png"/>
              <a id="companyLink" href="http://www.energypedia.info/Bioenergy" target="_blank">ADDAX Boienergy</a>
          </div>
          <div class="col-sm-2">
            <img src="images/ifc.png"/>
             <a id="companyLink" href="http://www.ifc.org/" target="_blank">International Finance cooperation</a>
          </div>
          <div class="col-md-2">
            <img src="images/predax.png"/>
              <a id="companyLink" href="http://www.pendraxsecurity.com" target="_blank"> Predax</a>
          </div>
          <div class="col-sm-2">
            <img src="images/rutile.png"/>
            <a id="companyLink" href="http://www.iluka.com/‎" target="_blank">  Sierra rutile limited</a>
          </div>
          <div class="col-sm-2">
            <img src="images/octea_logo.png"/>
            <a id="companyLink" href="http://www.koiduholdings.com/" target="_blank"> Octea Mining Company</a>
          </div>
          <div class="col-sm-2">
            <img src="images/concern.png"/>
            <a id="companyLink" href="https://www.concern.net/" target="_blank"> Concern</a>
          </div>
        </div><!--End of row two-->
      </div><!--End of Top Companies-->
      <div class="row"><!--Top Services of the Site-->
        <div class="col-sm-4 service_details">
          <i class="fa fa-file-pdf-o service_info"></i>
          <em class="h3 service_header">Resume Serivces</em>
          <p>
            We build tailor-made, highly configurable Online Resumes avaialable 24/7 anywhere and anytime.
          </p>
        </div>
        <div class="col-sm-4 service_details">
          <i class="fa fa-phone service_info" aria-hidden="true"></i>
          <em class="h3 service_header">Over Telephone Serice</em>
          <p>
            We build tailor-made, highly configurable Online Resumes avaialable 24/7 anywhere and anytime.
          </p>
        </div>
        <div class="col-sm-4 service_details">
          <i class="fa fa-folder-open service_info" aria-hidden="true"></i>
          <em class="h3">Placement Services</em>
          <p>
            We build tailor-made, highly configurable Online Resumes avaialable 24/7 anywhere and anytime.
          </p>
        </div>
      </div>
    </div>
        <?php include_once "footer.php"; ?><!--Site footer-->

  <?php include "library.php"; ?><!--Script Library files -->
  </body>
  </html>
