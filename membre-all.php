<?php
	if (!isset($_SESSION['login'])) {
		header ('Location: accueil');
		exit();
	} 
?>
<script type="text/javascript">
function validateDelete(){
	return confirm("Voulez-vous vraiment supprimer cette combinaison?");
}
</script>
<!-- Début de la présentation -->
<div id="presentation1"></div>
<!-- Fin de la présentation -->

<!-- Début du contenu -->
<div id="content">
	<div id="bloc">
		<div id="text-center">
			<div id="title">Espace membre </div>

	
<div id="container">
	<div id="col1">
	<h3>Vos combinaisons</h3>
					<?php
					$pseudo = $_SESSION['login'];
					if(isset($_POST['idListeCombi'])){
						$idCombi = $_POST['idListeCombi'];
						if(deleteCombinaisonByIdAndMembre($idCombi, $pseudo)){
							echo '<h3>Votre combinaison a bien été supprimée.</h3>';
						}
					}
					$query = getCombinaisonByPseudoLimit15($pseudo);
					$y = 1;
					if(sizeof($query) == 0) {
						echo 'Aucune combinaison créée. <br> <a href="combiner">Commencer maintenant</a> !';
					}
					else {
						foreach($query as $resultat1) {
							$titre = $resultat1->titre();
							$id = $resultat1->id_liste();
							$liste = $resultat1->liste();
							echo "$y. $titre"  
					?>
							<form method="post" action="revise"> 
								<input type="hidden" name="reviseCombi" value="ok" />
								<input type="hidden" name="reviseCombiMots" value="<?php echo $liste ?>" />
								<input type="hidden" name="titreCombi" value="<?php echo $titre ?>" />
								<input type="submit" name="combiner" value="Réviser cette combinaison" />
							</form>
							<form method="post" action="membre-all" name="supprimerCombi" onsubmit="return validateDelete();">
								<input type="hidden" name="idListeCombi" value="<?php echo $resultat1->id() ?>" />
								<input src="images/delete.png" type=image type="submit" name="supprimer" value="Supprimer cette combinaison" />
							</form>
							<br> 
					<?php
							$y++;
						}
					}
					?>
	</div>
	<div id="col2outer"> 
		<div id="col2mid">
		<p>
		<h3>20 dernières listes révisées</h3><?php
		$listeRevision = getRevisionsByPseudoLimit20($pseudo);
		$i = 1;
		if(sizeof($listeRevision) == 0) {
			echo 'Aucune liste révisée.<br><a href="?page=gerer-public">Commencer maintenant</a> !';
		}
		else {
			foreach($listeRevision as $revision) {
				$id = $revision->id_liste();
				if($id == 'no' || empty($id)) {
					$displayListe = 'Mots entrés par vous pour une utilisation unique';
				}
				else {
					$listesMots = getListeByPseudo($pseudo);
					if(empty($listesMots)){
						$displayListe = 'Liste supprimée';		
					}else{
						foreach($listesMots as $listeMots){
							$displayListe = '<a href="afficher?id='.$id.'">'.$listeMots->titre().'</a>';
						}
					}
				}
				?><?php echo $i ?>. <?php echo $displayListe ?> - <b>Moyenne de la révision: <?php echo $revision->moyenne() ?>%</b> - <small>Revisé le <?php echo $revision->date()?>. </small><br /><br /> <?php
				$i++;
			}
		}
		?>
		</div>
		<div id="col2side"> 
			<h3>Favoris</h3>
				<?php
				$membre = $_SESSION['login'];
				$requete_favoris = getFavoriByPseudoLimit50($membre);
				$nombre = sizeof($requete_favoris);
				if($nombre == 0){
					echo "Vous n'avez aucune liste en favoris.";
				}
				else {
					$i = 1;
					foreach($requete_favoris as $rendu) {
						$listeMots = getListeById($rendu->id_liste());
						echo ''.$i++.'. ';
						?><a href="afficher?id=<?php echo $listeMots->id() ?>"><?php echo $listeMots->titre() ?></a> - <small><?php echo $listeMots->categorie() ?> <-> <?php echo $listeMots->categorie2() ?></small><br /><?php
					}
				}
			?>		
		</div>
	</div>
</div> 
</div></div></div>