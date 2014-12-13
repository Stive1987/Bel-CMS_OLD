<?php
/**
 * Bel-CMS [Content management system]
 * @version 0.0.1
 * @link http://www.bel-cms.be
 * @link http://www.stive.eu
 * @license http://opensource.org/licenses/GPL-3.0 copyleft
 * @copyright 2014 Bel-CMS
 * @author Stive - mail@stive.eu
 */
if (!defined('CHECK_INDEX')) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Direct access forbidden');
    exit("<!DOCTYPE html>\r\n<html><head>\r\n<title>403 Direct access forbidden</title>\r\n</head><body>\r\n<h1>Direct access forbidden</h1>\r\n<p>The requested URL " . $_SERVER['SCRIPT_NAME'] . " is prohibited.</p>\r\n</body></html>");
}
if (isset($_SESSION['hash_key'])):
?>
	<section id="bel_cms_user_main">

		<div id="bel_cms_user_left">
			<div id="bel_cms_user_avatar">
				<?php
				if ($this->gravatar):
				?>
				<a href="https://fr.gravatar.com/">
					<img title="Modifier gravatar" class="tipN" src="<?php echo get_gravatar($this->private_mail, 200); ?>" alt="User Avatar">
				</a>
				<?php
				else:
				?>
				<img src="<?php echo $this->avatar; ?>" alt="User Avatar">
				<?php
				endif;
				?>
			</div>
			<?php
			?>
			<dl id="bel_cms_user_avatar_all">
				<dt>Mes avatars
					<a href="#userAvatarPage" id="add_avatar" class="user_change_page">
						<i class="ion-plus-circled"></i>
					</a>
				</dt>
				<?php
				$countAvatar = count($this->array_list_avatar['dir']) + count($this->array_list_avatar['bdd']);
				$merge_list_avatar = array_merge($this->array_list_avatar['dir'], $this->array_list_avatar['bdd']);
				if ($countAvatar != 0):
				$i = null;
				foreach ($merge_list_avatar as $v):
				$i++;
				?>
				<dd>
					<a title="Selectionner" class="select_avatar tipN" href="<?php echo $v; ?>">
						<img src="<?php echo $v; ?>" alt="avatar_<?php echo $this->name; ?>_<?php echo $i; ?>">
					</a>
				</dd>
				<?php
				endforeach;
				else:
				?>
				<dd>
					<p>Aucun avatar</p>
				</dd>
				<?php
				endif;
				?>
			</dl>
			<?php
			if (is_array($this->comments) and !empty($this->comments)):
			?>
			<dl id="bel_cms_user_last_comments">
				<dt>Récent commentaire<span>(<?php echo count($this->comments); ?>)</span></dt>
				<?php
				$i = null;
				foreach ($this->comments as $k => $v):
					$supp = ($v['modules'] == 'news') ? 'readmore/' : '';
				$i++;
				$link = $v['modules'].'/'.$supp.$v['id_mods'];
				?>
				<dd>
					<a href="<?php echo $link; ?>">
						<?php echo cutText($v['text'], 35); ?>
					</a>
				</dd>
				<?php
				endforeach;
				?>
			</dl>
			<?php
			endif;
			?>
		</div>

		<div id="bel_cms_user_right">
			<div id="bel_cms_user_name"><?php echo $this->name; ?></div>
			<div id="bel_cms_user_desc"><?php echo $this->info_text; ?></div>

			<ul id="bel_cms_user_right_menu">
				<li class="active"><a class="user_change_page" href="#user_main_page"><i class="ion-information"></i>Information</a></li>
				<li><a class="user_change_page" href="#userConfigPage"><i class="ion-ios7-cog-outline"></i>Config</a></li>
				<li><a id="bel_cms_logout" href="User/Logout/ajax"><i class="ion-log-out"></i></a></li>
			</ul>

			<div id="bel_cms_user_right_content">
				<section class="active" id="user_main_page">
					<div class="bel_cms_user_right_content">
						<div class="bel_cms_user_right_block" style="border-top-color: <?php echo random_color(); ?>;">
							<header>
								<h4>Information Personnel</h4>
								<a class="user_change_page" href="#userFormInfos" title="Editer"><i class="ion-compose"></i></a>
							</header>
							<div class="bel_cms_user_right_block_body">
								<ul>
									<li>Sexe<span><?php echo $this->gender; ?></span></li>
									<li>Email<span><?php echo $this->private_mail; ?></span></li>
									<li>Statut<span><?php echo $this->valid; ?></span></li>
									<li>Groupe principal<span><?php echo $this->main_groups; ?></span></li>
									<li>Inscription<span><?php echo $this->date_registration; ?></span></li>
									<li>Derniere visite<span><?php echo $this->last_visit; ?></span></li>
								</ul>
							</div>
						</div>
					</div>

					<div class="bel_cms_user_right_content">
						<div class="bel_cms_user_right_block" style="border-top-color: <?php echo random_color(); ?>;">
							<header>
								<h4>Information Contact</h4>
								<a class="user_change_page" href="#userFormInfos" title="Editer"><i class="ion-compose"></i></a>
							</header>
							<div class="bel_cms_user_right_block_body">
								<ul>
									<li>Email Publique<span><?php echo $this->public_mail; ?></span></li>
									<li>SiteWeb<span><?php echo $this->websites; ?></span></li>
								</ul>
							</div>
						</div>
						<div class="bel_cms_user_right_block" style="border-top-color: <?php echo random_color(); ?>;">
							<header>
								<h4>Liste des connexions</h4>
							</header>
							<div class="bel_cms_user_right_block_body">
								<ul>
									<?php 
									foreach ($this->connexions as $k => $v):
									?>
										<li>Date
											<span><?php echo $v['date']; ?></span>
											<div><?php echo $v['ip']; ?>
										</li>
									<?php
									endforeach;
									?>
								</ul>
							</div>
						</div>
					</div>
				</section>
				<?php
				userFormInfos ($this);
				userConfigPage ($this);
				userFormAvatar ($this->array_list_avatar);
				?>
			</div>
		</div>

	</section>
<?php
endif;
function userConfigPage ($data)
{
	if ($data->gravatar == 1) {
		$checkedGravatarOn  = 'checked="checked"';
		$checkedGravatarOff = '';
	} else {
		$checkedGravatarOn  = '';
		$checkedGravatarOff = 'checked="checked"';
	}
	?>
	<section id="userConfigPage">
		<div class="bel_cms_user_block" style="border-top-color: <?php echo random_color(); ?>;">
			<form id="formSendConfig" action="User/send/ajax" method="post" accept-charset="utf-8">
				<header>
					<h4>Utilisé gravatar</h4>
				</header>
				<p class="four">
					<label for="gravatar_on">Activer</label>
					<input id="gravatar_on" type="radio" name="gravatar" value="1" <?php echo $checkedGravatarOn; ?>>
					<label for="gravatar_off">Désactiver</label>
					<input id="gravatar_off" type="radio" name="gravatar" value="0" <?php echo $checkedGravatarOff; ?>>
				</p>
				<header>
					<h4>Changement de mot de passe</h4>
				</header>
				<p class="two">
					<label for="password_new">Mot de passe (Nouveau)</label>
					<input placeholder="à remplir uniquement en cas de changement" id="password_new" type="password" name="password_new" value="" autocomplete="off">
				</p>
				<p class="two">
					<label for="password">Mot de passe (Ancien)</label>
					<input placeholder="Obligatoire" id="password" type="password" name="password" value="" autocomplete="off" required="required">
				</p>
				<p class="full">
					<input type="hidden" name="type" value="config">
					<input class="button" type="submit" value="<?php echo CONFIRM; ?>">
				</p>
			</form>
		</div>
	</section>
	<?php
}
function userFormAvatar ($data)
{
?>
	<section id="userAvatarPage">
		<div class="bel_cms_user_block" style="border-top-color: <?php echo random_color(); ?>;">
			<form id="formSendAvatar" action="User/sendAvatar/ajax" method="post" accept-charset="utf-8" enctype="multipart/form-data">
				<header>
					<h4>Upload avatar</h4>
				</header>
				<p class="two">
					<label for="file_avatar">Upload ( max : <?php echo convert_size(max_upload_file()); ?>)</label>
					<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo max_upload_file(); ?>">
					<input id="file_avatar" type="file" name="avatar" value="" placeholder="Choisissez votre avatar">
				</p>
				<p class="full">
					<div id="progress">
						<div id="progress-bar"></div>
					</div>
				</p>
			</form>
		</div>
		<?php
		if (!empty($data)):
		?>
		<div class="bel_cms_user_block" style="border-top-color: <?php echo random_color(); ?>;">
			<header>
				<h4>Supprimer avatar</h4>
			</header>
			<ul id="userUlAvatar">
			<?php
			$i = null;
			foreach ($data['dir'] as $k => $v):
				$i++;
				?>
				<li>
					<a class="user_del_avatar" data-type="dir" href="#<?php echo $v; ?>">
						<img src="<?php echo $v; ?>" alt="avatar_<?php echo $i; ?>">
						<span><?php echo $v; ?></span>
						<i class="ion-backspace"></i>
					</a>
				</li>
				<?php
			endforeach;
			foreach ($data['bdd'] as $k => $v):
				$i++;
				?>
				<li>
					<a class="user_del_avatar" data-type="bdd" href="#<?php echo $v; ?>">
						<img src="<?php echo $v; ?>" alt="avatar_<?php echo $i; ?>">
						<span><?php echo $v; ?></span>
						<i class="ion-backspace"></i>
					</a>
				</li>
				<?php
			endforeach;
			?>
			</ul>
		</div>
		<?php
		endif;
		?>
	</section>
<?php
}
function userFormInfos ($data)
{
	if ($data->gender == MAN) {
		$checkedGenderMan   = 'checked="checked"';
		$checkedGenderWoman = '';
	} else {
		$checkedGenderMan   = '';
		$checkedGenderWoman = 'checked="checked"';
	}
	?>
	<section id="userFormInfos">
		<div class="bel_cms_user_block" style="border-top-color: <?php echo random_color(); ?>;">
			<header>
				<h4>Modifier vos information</h4>
			</header>
			<form id="formSendAccount" action="User/send/ajax" method="post" accept-charset="utf-8">
				<p class="four">
					<label for="sex1">Homme</label>
					<input id="sex1" type="radio" name="sex" value="0" <?php echo $checkedGenderMan; ?>></label>
					<label for="sex2">Femme</label>
					<input id="sex2" type="radio" name="sex" value="1" <?php echo $checkedGenderWoman; ?>></label>
				</p>
				<p class="two">
					<label for="private_mail">Email Privé</label>
					<input id="private_mail" type="email" name="private_mail" value="<?php echo $data->private_mail; ?>" required="required">
				</p>
				<p class="two">
					<label for="public_mail">Email Public</label>
					<input id="public_mail" type="email" name="public_mail" value="<?php echo $data->public_mail; ?>">
				<p class="two">
					<label for="website">Site Web</label>
					<input id="website" type="url" name="website" value="<?php echo $data->websites; ?>">
				</p>
				<p class="two">
					<label for="password">Mot de passe</label>
					<input placeholder="Obligatoire" id="password" type="password" name="password" value="" autocomplete="off" required="required">
				</p>
				<header>
					<h4>Signature / Information</h4>
				</header>
				<p class="full">
					<textarea id="user_info_text" class="editor_light" name="info_text"><?php echo $data->info_text; ?></textarea>
				</p>
				<p class="full">
					<input type="hidden" name="type" value="account">
					<input class="button" type="submit" value="<?php echo CONFIRM; ?>">
				</p>
			</form>
		</div>
	</section>
	<?php
}
?>