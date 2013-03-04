<?php
require_once('lib/LibRDF/LibRDF.php');

function query($person){ 
    // All models, i.e. graphs, reside in a storage. This defaults to
    // memory.
    $store = new LibRDF_Storage();
    $model = new LibRDF_Model($store);

    // Load some data into the model. The format must explicitly be
    // declared for the parser, but using e.g. ARC's format detector
    // should be easy to implement. Anyways, in this case we're
    // dealing with an RDF/XML document:
    $model->loadStatementsFromURI(
            new LibRDF_Parser('rdfxml'),
            'http://owl.dev/ontologies/rolemap.rdf');
    
    $prefix = " PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
                PREFIX owl: <http://www.w3.org/2002/07/owl#>
                PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
                PREFIX kgd: <http://localhost/ontologies/rolemap.rdf#>";
    
    // Create a SPARQL query
    $sparql = " SELECT ?role WHERE {
                    ?role kgd:is_role_of ?Person .
                    ?Person kgd:hasfullname \"".$person."\"^^xsd:string 
                }";
    $query = new LibRDF_Query($prefix . $sparql, null, 'sparql');

    // Execute the query. The results of a SPARQL SELECT provide
    // array access by using the variables used in the query as keys:
    $query = $query->execute($model);
    
    foreach($query as $result){
        $resultArr['post']['role'] = str_replace("_", " ",formatSet($result['role']));
        
    }
    
    $sparql = " SELECT ?context

                WHERE {?context kgd:is_personcontext_of ?person .
                ?person kgd:hasfullname \"".$person."\"^^xsd:string
                }";
    $query = new LibRDF_Query($prefix . $sparql, null, 'sparql');
    
    $query = $query->execute($model);
    
    foreach($query as $result){
        $resultArr['post']['context'] = formatSet($result['context']);
    }
    
    $sparql = " SELECT ?know_what
                WHERE {?know_what kgd:is_personknowwhat_of ?Person .
                ?Person kgd:hasfullname \"".$person."\"^^xsd:string
                }";
    
    $query = new LibRDF_Query($prefix . $sparql, null, 'sparql');
    
    $query = $query->execute($model);
    
    foreach($query as $i => $result){
        $resultArr['competence_known']['know_what'][$i] = formatSet($result['know_what']);
    }
    
    $sparql = " SELECT ?know_how
                WHERE {?know_how kgd:is_personknowhow_of ?Person .
                ?Person kgd:hasfullname \"".$person."\"^^xsd:string
                }";
    
    $query = new LibRDF_Query($prefix . $sparql, null, 'sparql');
    
    $query = $query->execute($model);
    
    foreach($query as $i => $result){
        $resultArr['competence_known']['know_how'][$i] = formatSet($result['know_how']);
    }
    
    $sparql = " SELECT ?know_why
                WHERE {?know_why kgd:is_personknowwhy_of ?Person .
                ?Person kgd:hasfullname \"".$person."\"^^xsd:string
                }";
    
    $query = new LibRDF_Query($prefix . $sparql, null, 'sparql');
    
    $query = $query->execute($model);
    
    foreach($query as $i => $result){
        $resultArr['competence_known']['know_why'][$i] = formatSet($result['know_why']);
    }
    
    $sparql = " SELECT ?know_with
                WHERE {?know_with kgd:is_personknowwith_of ?Person .
                ?Person kgd:hasfullname \"".$person."\"^^xsd:string
                }";
    
    $query = new LibRDF_Query($prefix . $sparql, null, 'sparql');
    
    $query = $query->execute($model);
    
    foreach($query as $i => $result){
        $resultArr['competence_known']['know_with'][$i] = formatSet($result['know_with']);
    }
    
    $sparql = " SELECT ?know_who
                WHERE {?know_who kgd:is_personknowwho_of ?Person .
                ?Person kgd:hasfullname \"".$person."\"^^xsd:string
                }";
    
    $query = new LibRDF_Query($prefix . $sparql, null, 'sparql');
    
    $query = $query->execute($model);
    
    foreach($query as $i => $result){
        $resultArr['competence_known']['know_who'][$i] = formatSet($result['know_who']);
    }
    
    $sparql = " SELECT ?know_where
                WHERE {?know_where kgd:is_personknowwhere_of ?Person .
                ?Person kgd:hasfullname \"".$person."\"^^xsd:string
                }";
    
    $query = new LibRDF_Query($prefix . $sparql, null, 'sparql');
    
    $query = $query->execute($model);
    
    foreach($query as $i => $result){
        $resultArr['competence_known']['know_where'][$i] = formatSet($result['know_where']);
    }
    
    $sparql = " SELECT ?know_when
                WHERE {?know_when kgd:is_personknowwhen_of ?Person .
                ?Person kgd:hasfullname \"".$person."\"^^xsd:string
                }";
    
    $query = new LibRDF_Query($prefix . $sparql, null, 'sparql');
    
    $query = $query->execute($model);
    
    foreach($query as $i => $result){
        $resultArr['competence_known']['know_when'][$i] = formatSet($result['know_when']);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    $sparql = " SELECT ?know_what

                WHERE {?know_what kgd:is_knowwhat_of ?Task .
                ?Task kgd:is_task_of ?Activity .
                ?Activity kgd:is_activity_of ?Role .
                ?Role kgd:hasrolename \"".$resultArr['post']['role']."\"^^xsd:string
                }";
    
    $query = new LibRDF_Query($prefix . $sparql, null, 'sparql');
    
    $query = $query->execute($model);
    
    foreach($query as $i => $result){
        $resultArr['competence_needed']['know_what'][$i] = formatSet($result['know_what']);
    }
    
    $sparql = " SELECT ?know_how

                WHERE {?know_how kgd:is_knowhow_of ?Task .
                ?Task kgd:is_task_of ?Activity .
                ?Activity kgd:is_activity_of ?Role .
                ?Role kgd:hasrolename \"".$resultArr['post']['role']."\"^^xsd:string
                }";
    
    $query = new LibRDF_Query($prefix . $sparql, null, 'sparql');
    
    $query = $query->execute($model);
    
    foreach($query as $i => $result){
        $resultArr['competence_needed']['know_how'][$i] = formatSet($result['know_how']);
    }
    
    $sparql = " SELECT ?know_why

                WHERE {?know_why kgd:is_knowwhy_of ?Task .
                ?Task kgd:is_task_of ?Activity .
                ?Activity kgd:is_activity_of ?Role .
                ?Role kgd:hasrolename \"".$resultArr['post']['role']."\"^^xsd:string
                }";
    
    $query = new LibRDF_Query($prefix . $sparql, null, 'sparql');
    
    $query = $query->execute($model);
    
    foreach($query as $i => $result){
        $resultArr['competence_needed']['know_why'][$i] = formatSet($result['know_why']);
    }
    
    $sparql = " SELECT ?know_with

                WHERE {?know_with kgd:is_knowwith_of ?Task .
                ?Task kgd:is_task_of ?Activity .
                ?Activity kgd:is_activity_of ?Role .
                ?Role kgd:hasrolename \"".$resultArr['post']['role']."\"^^xsd:string
                }";
    
    $query = new LibRDF_Query($prefix . $sparql, null, 'sparql');
    
    $query = $query->execute($model);
    
    foreach($query as $i => $result){
        $resultArr['competence_needed']['know_with'][$i] = formatSet($result['know_with']);
    }
    
    $sparql = " SELECT ?know_who

                WHERE {?know_who kgd:is_knowwho_of ?Task .
                ?Task kgd:is_task_of ?Activity .
                ?Activity kgd:is_activity_of ?Role .
                ?Role kgd:hasrolename \"".$resultArr['post']['role']."\"^^xsd:string
                }";
    
    $query = new LibRDF_Query($prefix . $sparql, null, 'sparql');
    
    $query = $query->execute($model);
    
    foreach($query as $i => $result){
        $resultArr['competence_needed']['know_who'][$i] = formatSet($result['know_who']);
    }
    
    $sparql = " SELECT ?know_where

                WHERE {?know_where kgd:is_knowwhere_of ?Task .
                ?Task kgd:is_task_of ?Activity .
                ?Activity kgd:is_activity_of ?Role .
                ?Role kgd:hasrolename \"".$resultArr['post']['role']."\"^^xsd:string
                }";
    
    $query = new LibRDF_Query($prefix . $sparql, null, 'sparql');
    
    $query = $query->execute($model);
    
    foreach($query as $i => $result){
        $resultArr['competence_needed']['know_where'][$i] = formatSet($result['know_where']);
    }
    
    $sparql = " SELECT ?know_when

                WHERE {?know_when kgd:is_knowwhen_of ?Task .
                ?Task kgd:is_task_of ?Activity .
                ?Activity kgd:is_activity_of ?Role .
                ?Role kgd:hasrolename \"".$resultArr['post']['role']."\"^^xsd:string
                }";
    
    $query = new LibRDF_Query($prefix . $sparql, null, 'sparql');
    
    $query = $query->execute($model);
    
    foreach($query as $i => $result){
        $resultArr['competence_needed']['know_when'][$i] = formatSet($result['know_when']);
    }
    
    
    $resultArr['competence_needed']["know_what"] = array_unique($resultArr['competence_needed']['know_what']);
    $resultArr['competence_needed']['know_how'] = array_unique($resultArr['competence_needed']['know_how']);
    $resultArr['competence_needed']['know_why'] = array_unique($resultArr['competence_needed']['know_why']);
    $resultArr['competence_needed']['know_with'] = array_unique($resultArr['competence_needed']['know_with']);
    $resultArr['competence_needed']['know_who'] = array_unique($resultArr['competence_needed']['know_who']);
    $resultArr['competence_needed']['know_where'] = array_unique($resultArr['competence_needed']['know_where']);
    $resultArr['competence_needed']['know_when'] = array_unique($resultArr['competence_needed']['know_when']);
    
    return $resultArr;
}

function formatSet($string){
    $explodeArr = explode("<http://localhost/ontologies/rolemap.rdf#",$string);
    $explodeArr = explode(">", $explodeArr[1]);
    return $explodeArr[0];
}
?>