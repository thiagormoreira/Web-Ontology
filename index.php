<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Template &middot; Bootstrap</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <link href="/assets/css/bootstrap.css" rel="stylesheet">
        <style type="text/css">
            body {
                padding-top: 20px;
                padding-bottom: 40px;
            }

            /* Custom container */
            .container-narrow {
                margin: 0 auto;
                max-width: 700px;
            }
            .container-narrow > hr {
                margin: 30px 0;
            }

            /* Main marketing message and sign up button */
            .jumbotron {
                margin: 60px 0;
                text-align: center;
            }
            .jumbotron h1 {
                font-size: 72px;
                line-height: 1;
            }
            .jumbotron .btn {
                font-size: 21px;
                padding: 14px 24px;
            }

            /* Supporting marketing content */
            .marketing {
                margin: 60px 0;
            }
            .marketing p + h4 {
                margin-top: 28px;
            }
        </style>
        <link href="/assets/css/bootstrap-responsive.css" rel="stylesheet">

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Fav and touch icons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="/assets/ico/apple-touch-icon-57-precomposed.png">
        <link rel="shortcut icon" href="/assets/ico/favicon.png">
    </head>

    <body>

        <div class="container-narrow">

            <div class="masthead">
                <ul class="nav nav-pills pull-right">
                    <li class="active"><a href="#">Home</a></li>
                    <li><a href="#">Sobre</a></li>
                    <li><a href="#">Contato</a></li>
                </ul>
                <h3 class="muted">Ontologia Web</h3>
            </div>

            <hr>

            <div class="jumbotron">
                <h1>Ontologia com PHP!</h1>
                <p class="lead">Ontologia Web com PHP</p>
            </div>

            <hr>

            <h3>Pesquise um funcionário</h3>
            <form method="POST" action="/">
                <div class="bs-docs-example" style="background-color: #f5f5f5;">
                    Digite um nome:
                    <input type="text" id="person_name" name="person_name" class="span3" style="margin: 0 auto;" data-provide="typeahead" data-items="4" data-source='["Anna Marie","Charles Xavier","Eric Lehnsherr","Hank McCoy","Jean Grey","Katherine Pride","Kurt Wagner","Ororo Monroe","Raven Darkholme","Robert Drake","Scott Summers","Victor Creed"]'>
                </div>
            </form>

            <div class="row-fluid marketing">
                <?php
                if (!empty($_POST) === true) {
                    require_once 'ontologies/sparql.php';
                    $results = query($_POST['person_name']);
                    echo "<pre> Cargo: " . $results['post']['role'] . " => " . $results['post']['context'] . "</pre>";
                    
                    
                    $needed = count($results['competence_needed'], true);
                    $known = count($results['competence_known'], true);
                    
                    $knownPerc = number_format($known * 100 / $needed, 2);
                    $neededPerc = 100 - $knownPerc;  
                
                echo '
                <h4>Nome que nao sei</h4>
                <div class="progress">
                    <div class="bar bar-success" style="width: ' . $knownPerc . '%;">' . $knownPerc . '%</div>
                    <div class="bar bar-danger" style="width: ' . $neededPerc . '%;">' . $neededPerc . '%</div>
                </div>';
                    
                    echo"
                <div>
                  <h4>Competências que faltam para o atual cargo</h4>
                  <p>Deficiência atual do funcionário</p>
                  ";
                
                    echo '<div class="accordion" id="accordion2">
                            <div class="accordion-group">
                              <div class="accordion-heading">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                                Know_What</a>
                                </div>
                                <div id="collapseOne" class="accordion-body collapse in">
                                  <div class="accordion-inner">
                                    <pre>';
                                       print_r(array("know_what" => array_diff($results['competence_needed']['know_what'], $results['competence_known']['know_what'])));
                    echo '          </pre> 
                                  </div>
                                </div>
                            </div>';
                    
                    echo '<div class="accordion-group">
                            <div class="accordion-heading">
                              <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                                Know_How
                              </a>
                            </div>
                            <div id="collapseTwo" class="accordion-body collapse">
                              <div class="accordion-inner">
                                <pre>';
                                    print_r(array("know_how" => array_diff($results['competence_needed']['know_how'], $results['competence_known']['know_how'])));
                    echo '      </pre>
                             </div>
                            </div>
                          </div>';
                    
                    echo '<div class="accordion-group">
                            <div class="accordion-heading">
                              <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTree">
                                Know_Why
                              </a>
                            </div>
                            <div id="collapseTree" class="accordion-body collapse">
                              <div class="accordion-inner">
                                <pre>';
                                    print_r(array("know_why" => array_diff($results['competence_needed']['know_why'], $results['competence_known']['know_why'])));
                    echo '      </pre>
                              </div>
                            </div>
                          </div>';
                    
                    echo '<div class="accordion-group">
                            <div class="accordion-heading">
                              <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseFour">
                                Know_With
                              </a>
                            </div>
                            <div id="collapseFour" class="accordion-body collapse">
                              <div class="accordion-inner">
                                <pre>';
                                    print_r(array("know_with" => array_diff($results['competence_needed']['know_with'], $results['competence_known']['know_with'])));
                    echo '      </pre>
                              </div>
                            </div>
                          </div>';
                    
                    echo '<div class="accordion-group">
                            <div class="accordion-heading">
                              <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseFive">
                                Know_Who
                              </a>
                            </div>
                            <div id="collapseFive" class="accordion-body collapse">
                              <div class="accordion-inner">
                                <pre>';
                                    print_r(array("know_who" => array_diff($results['competence_needed']['know_who'], $results['competence_known']['know_who'])));
                    echo '      </pre>
                            </div>
                           </div>
                          </div>';
                
                    echo '<div class="accordion-group">
                            <div class="accordion-heading">
                              <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseSix">
                                Know_Where
                              </a>
                            </div>
                            <div id="collapseSix" class="accordion-body collapse">
                              <div class="accordion-inner">
                                <pre>';
                                    print_r(array("know_where" => array_diff($results['competence_needed']['know_where'], $results['competence_known']['know_where'])));
                    echo '      </pre>
                               </div>
                              </div>
                            </div>';
                    
                    echo '<div class="accordion-group">
                            <div class="accordion-heading">
                              <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseSeven">
                                Know_When
                              </a>
                            </div>
                            <div id="collapseSeven" class="accordion-body collapse">
                              <div class="accordion-inner">
                                <pre>';
                                    print_r(array("know_when" => array_diff($results['competence_needed']['know_when'], $results['competence_known']['know_when'])));
                    echo '      </pre>
                              </div>
                            </div>
                          </div>';
                    
                    
                    echo "</div>
                        </div>
                       </div>";

                    echo "<div>
                </div>
                ";
                    
                    echo "
                <div>
                  <h4>Competências pessoias</h4>
                  <p>Competências pertencentes à pessoa procurada</p>
                ";

                    echo "<pre>";
                    print_r($results['competence_known']);
                    echo "</pre>";

                    echo "
                </div>
                ";

                    echo"
                <div>
                  <h4>Competências do cargo</h4>
                  <p>Competências pertencentes ao cargo ocupado atualmente</p>
                  ";

                    echo "<pre>";
                    print_r($results['competence_needed']);
                    echo "</pre>";

                    echo "
                </div>
                  ";
                }
                ?>
            </div>

            <hr>

            <div class="footer">
                <p>&copy; Unirio 2012</p>
            </div>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="/assets/js/jquery.js"></script>
        <script src="/assets/js/bootstrap-typeahead.js"></script>
        <script src="/assets/js/bootstrap-collapse.js"></script>
        <script src="/assets/js/bootstrap-transition.js"></script>
    </body>
</html>
