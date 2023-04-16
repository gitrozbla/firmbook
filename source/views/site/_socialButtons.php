<?php
/*
 * social buttons
 */
?>

<?php $cs = Yii::app()->clientScript; ?>

<?php $cs->registerCssFile(Yii::app()->homeUrl . 'js/vendor/rrssb/css/rrssb.css'); ?>

<div class="pull-right">
  <div class="fb-like inline margin-8" data-href="<?php echo $url; ?>"
      data-layout="button_count" data-action="like" data-show-faces="false"></div>

	<div class="rrssb-buttons-wrapper">
	  
		<div><?php echo Yii::t('site', 'Share/Community') ?>:</div>

		<!-- Buttons start here. Copy this ul to your document. -->
		<ul class="rrssb-buttons clearfix">

		  <li class="rrssb-email">
				<a href="#" rel="nofollow">
				  <span class="rrssb-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28"><path d="M20.11 26.147c-2.335 1.05-4.36 1.4-7.124 1.4C6.524 27.548.84 22.916.84 15.284.84 7.343 6.602.45 15.4.45c6.854 0 11.8 4.7 11.8 11.252 0 5.684-3.193 9.265-7.398 9.3-1.83 0-3.153-.934-3.347-2.997h-.077c-1.208 1.986-2.96 2.997-5.023 2.997-2.532 0-4.36-1.868-4.36-5.062 0-4.75 3.503-9.07 9.11-9.07 1.713 0 3.7.4 4.6.972l-1.17 7.203c-.387 2.298-.115 3.3 1 3.4 1.674 0 3.774-2.102 3.774-6.58 0-5.06-3.27-8.994-9.304-8.994C9.05 2.87 3.83 7.545 3.83 14.97c0 6.5 4.2 10.2 10 10.202 1.987 0 4.09-.43 5.647-1.245l.634 2.22zM16.647 10.1c-.31-.078-.7-.155-1.207-.155-2.572 0-4.596 2.53-4.596 5.53 0 1.5.7 2.4 1.9 2.4 1.44 0 2.96-1.83 3.31-4.088l.592-3.72z"/></svg>
				  </span>
				  <span class="rrssb-text">email</span>
				</a>
			  </li>

			  <li class="rrssb-facebook">
				<a href="#" class="popup" rel="nofollow">
				  <span class="rrssb-icon">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29 29"><path d="M26.4 0H2.6C1.714 0 0 1.715 0 2.6v23.8c0 .884 1.715 2.6 2.6 2.6h12.393V17.988h-3.996v-3.98h3.997v-3.062c0-3.746 2.835-5.97 6.177-5.97 1.6 0 2.444.173 2.845.226v3.792H21.18c-1.817 0-2.156.9-2.156 2.168v2.847h5.045l-.66 3.978h-4.386V29H26.4c.884 0 2.6-1.716 2.6-2.6V2.6c0-.885-1.716-2.6-2.6-2.6z"/></svg>
				  </span>
				  <span class="rrssb-text">facebook</span>
				</a>
			  </li>

			  <?php /*
			  <li class="rrssb-instagram">
				<a href="#">
				  <span class="rrssb-icon">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28"><path d="M4.066.636h19.867c1.887 0 3.43 1.543 3.43 3.43v19.868c0 1.888-1.543 3.43-3.43 3.43H4.066c-1.887 0-3.43-1.542-3.43-3.43V4.066c0-1.887 1.544-3.43 3.43-3.43zm16.04 2.97c-.66 0-1.203.54-1.203 1.202v2.88c0 .662.542 1.203 1.204 1.203h3.02c.663 0 1.204-.54 1.204-1.202v-2.88c0-.662-.54-1.203-1.202-1.203h-3.02zm4.238 8.333H21.99c.224.726.344 1.495.344 2.292 0 4.446-3.72 8.05-8.308 8.05s-8.31-3.604-8.31-8.05c0-.797.122-1.566.344-2.293H3.606v11.29c0 .584.48 1.06 1.062 1.06H23.28c.585 0 1.062-.477 1.062-1.06V11.94h.002zm-10.32-3.2c-2.963 0-5.367 2.33-5.367 5.202 0 2.873 2.404 5.202 5.368 5.202 2.965 0 5.368-2.33 5.368-5.202s-2.403-5.2-5.368-5.2z"/></svg>
				  </span>
				  <span class="rrssb-text">instagram</span>
				</a>
			  </li>

			  <li class="rrssb-tumblr">
				<a href="#">
				  <span class="rrssb-icon">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28"><path d="M18.02 21.842c-2.03.052-2.422-1.396-2.44-2.446v-7.294h4.73V7.874H15.6V1.592h-3.714s-.167.053-.182.186c-.218 1.935-1.144 5.33-4.988 6.688v3.637h2.927v7.677c0 2.8 1.7 6.7 7.3 6.6 1.863-.03 3.934-.795 4.392-1.453l-1.22-3.54c-.52.213-1.415.413-2.115.455z"/></svg>
				  </span>
				  <span class="rrssb-text">tumblr</span>
				</a>
			  </li>
			  */ ?>

			  <li class="rrssb-linkedin">
				<a href="#" class="popup" rel="nofollow">
				  <span class="rrssb-icon">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28"><path d="M25.424 15.887v8.447h-4.896v-7.882c0-1.98-.71-3.33-2.48-3.33-1.354 0-2.158.91-2.514 1.802-.13.315-.162.753-.162 1.194v8.216h-4.9s.067-13.35 0-14.73h4.9v2.087c-.01.017-.023.033-.033.05h.032v-.05c.65-1.002 1.812-2.435 4.414-2.435 3.222 0 5.638 2.106 5.638 6.632zM5.348 2.5c-1.676 0-2.772 1.093-2.772 2.54 0 1.42 1.066 2.538 2.717 2.546h.032c1.71 0 2.77-1.132 2.77-2.546C8.056 3.593 7.02 2.5 5.344 2.5h.005zm-2.48 21.834h4.896V9.604H2.867v14.73z"/></svg>
				  </span>
				  <span class="rrssb-text">linkedin</span>
				</a>
			  </li>

			  <li class="rrssb-twitter">
				<a href="#"
				class="popup" rel="nofollow">
				  <span class="rrssb-icon">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28"><path d="M24.253 8.756C24.69 17.08 18.297 24.182 9.97 24.62a15.093 15.093 0 0 1-8.86-2.32c2.702.18 5.375-.648 7.507-2.32a5.417 5.417 0 0 1-4.49-3.64c.802.13 1.62.077 2.4-.154a5.416 5.416 0 0 1-4.412-5.11 5.43 5.43 0 0 0 2.168.387A5.416 5.416 0 0 1 2.89 4.498a15.09 15.09 0 0 0 10.913 5.573 5.185 5.185 0 0 1 3.434-6.48 5.18 5.18 0 0 1 5.546 1.682 9.076 9.076 0 0 0 3.33-1.317 5.038 5.038 0 0 1-2.4 2.942 9.068 9.068 0 0 0 3.02-.85 5.05 5.05 0 0 1-2.48 2.71z"/></svg>
				  </span>
				  <span class="rrssb-text">twitter</span>
				</a>
			  </li>

			  <?php /*
			  <li class="rrssb-reddit">
				<a href="#">
				  <span class="rrssb-icon">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28"><path d="M11.794 15.316c0-1.03-.835-1.895-1.866-1.895-1.03 0-1.893.866-1.893 1.896s.863 1.9 1.9 1.9c1.023-.016 1.865-.916 1.865-1.9zM18.1 13.422c-1.03 0-1.895.864-1.895 1.895 0 1 .9 1.9 1.9 1.865 1.03 0 1.87-.836 1.87-1.865-.006-1.017-.875-1.917-1.875-1.895zM17.527 19.79c-.678.68-1.826 1.007-3.514 1.007h-.03c-1.686 0-2.834-.328-3.51-1.005a.677.677 0 0 0-.958 0c-.264.265-.264.7 0 1 .943.9 2.4 1.4 4.5 1.402.005 0 0 0 0 0 .005 0 0 0 0 0 2.066 0 3.527-.46 4.47-1.402a.678.678 0 0 0 .002-.958c-.267-.334-.688-.334-.988-.043z"/><path d="M27.707 13.267a3.24 3.24 0 0 0-3.236-3.237c-.792 0-1.517.287-2.08.76-2.04-1.294-4.647-2.068-7.44-2.218l1.484-4.69 4.062.955c.07 1.4 1.3 2.6 2.7 2.555a2.696 2.696 0 0 0 2.695-2.695C25.88 3.2 24.7 2 23.2 2c-1.06 0-1.98.616-2.42 1.508l-4.633-1.09a.683.683 0 0 0-.803.454l-1.793 5.7C10.55 8.6 7.7 9.4 5.6 10.75c-.594-.45-1.3-.75-2.1-.72-1.785 0-3.237 1.45-3.237 3.2 0 1.1.6 2.1 1.4 2.69-.04.27-.06.55-.06.83 0 2.3 1.3 4.4 3.7 5.9 2.298 1.5 5.3 2.3 8.6 2.325 3.227 0 6.27-.825 8.57-2.325 2.387-1.56 3.7-3.66 3.7-5.917 0-.26-.016-.514-.05-.768.965-.465 1.577-1.565 1.577-2.698zm-4.52-9.912c.74 0 1.3.6 1.3 1.3a1.34 1.34 0 0 1-2.683 0c.04-.655.596-1.255 1.396-1.3zM1.646 13.3c0-1.038.845-1.882 1.883-1.882.31 0 .6.1.9.21-1.05.867-1.813 1.86-2.26 2.9-.338-.328-.57-.728-.57-1.26zm20.126 8.27c-2.082 1.357-4.863 2.105-7.83 2.105-2.968 0-5.748-.748-7.83-2.105-1.99-1.3-3.087-3-3.087-4.782 0-1.784 1.097-3.484 3.088-4.784 2.08-1.358 4.86-2.106 7.828-2.106 2.967 0 5.7.7 7.8 2.106 1.99 1.3 3.1 3 3.1 4.784C24.86 18.6 23.8 20.3 21.8 21.57zm4.014-6.97c-.432-1.084-1.19-2.095-2.244-2.977.273-.156.59-.245.928-.245 1.036 0 1.9.8 1.9 1.9a2.073 2.073 0 0 1-.57 1.327z"/></svg>
				  </span>
				  <span class="rrssb-text">reddit</span>
				</a>
			  </li>
			  */ ?>

			  <li class="rrssb-vk">
			  <a href="http://vk.com/share.php?url=<?php echo urlencode($url) ?>" class="popup" rel="nofollow">
				<span class="rrssb-icon">
				  <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="70 70 378.7 378.7"><path d="M254.998 363.106h21.217s6.408-.706 9.684-4.23c3.01-3.24 2.914-9.32 2.914-9.32s-.415-28.47 12.796-32.663c13.03-4.133 29.755 27.515 47.482 39.685 13.407 9.206 23.594 7.19 23.594 7.19l47.407-.662s24.797-1.53 13.038-21.027c-.96-1.594-6.85-14.424-35.247-40.784-29.728-27.59-25.743-23.126 10.063-70.85 21.807-29.063 30.523-46.806 27.8-54.405-2.596-7.24-18.636-5.326-18.636-5.326l-53.375.33s-3.96-.54-6.892 1.216c-2.87 1.716-4.71 5.726-4.71 5.726s-8.452 22.49-19.714 41.618c-23.77 40.357-33.274 42.494-37.16 39.984-9.037-5.842-6.78-23.462-6.78-35.983 0-39.112 5.934-55.42-11.55-59.64-5.802-1.4-10.076-2.327-24.915-2.48-19.046-.192-35.162.06-44.29 4.53-6.072 2.975-10.757 9.6-7.902 9.98 3.528.47 11.516 2.158 15.75 7.92 5.472 7.444 5.28 24.154 5.28 24.154s3.145 46.04-7.34 51.758c-7.193 3.922-17.063-4.085-38.253-40.7-10.855-18.755-19.054-39.49-19.054-39.49s-1.578-3.873-4.398-5.947c-3.42-2.51-8.2-3.307-8.2-3.307l-50.722.33s-7.612.213-10.41 3.525c-2.488 2.947-.198 9.036-.198 9.036s39.707 92.902 84.672 139.72c41.234 42.93 88.048 40.112 88.048 40.112"/></svg>
				</span>
				<span class="rrssb-text">vk.com</span>
			  </a>
			</li>

			<?php /*
			  <li class="rrssb-hackernews">
				<a href="#">
				  <span class="rrssb-icon">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28"><path d="M14 13.626l-4.508-9.19H6.588l6.165 12.208v6.92h2.51v-6.92l6.15-12.21H18.69z"/></svg>
				  </span>
				  <span class="rrssb-text">hackernews</span>
				</a>
			  </li>
			  */ ?>

			  <li class="rrssb-googleplus">
				<a href="#" class="popup" rel="nofollow">
				  <span class="rrssb-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M21 8.29h-1.95v2.6h-2.6v1.82h2.6v2.6H21v-2.6h2.6v-1.885H21V8.29zM7.614 10.306v2.925h3.9c-.26 1.69-1.755 2.925-3.9 2.925-2.34 0-4.29-2.016-4.29-4.354s1.885-4.353 4.29-4.353c1.104 0 2.014.326 2.794 1.105l2.08-2.08c-1.3-1.17-2.924-1.883-4.874-1.883C3.65 4.586.4 7.835.4 11.8s3.25 7.212 7.214 7.212c4.224 0 6.953-2.988 6.953-7.082 0-.52-.065-1.104-.13-1.624H7.614z"/></svg>            </span>
				  <span class="rrssb-text">google+</span>
				</a>
			  </li>

			  <?php /*
			  <li class="rrssb-pocket">
				<a href="#">
				  <span class="rrssb-icon">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M28.782.002c2.03.002 3.193 1.12 3.182 3.106-.022 3.57.17 7.16-.158 10.7-1.09 11.773-14.588 18.092-24.6 11.573C2.72 22.458.197 18.313.057 12.937c-.09-3.36-.05-6.72-.026-10.08C.04 1.113 1.212.016 3.02.008 7.347-.006 11.678.004 16.006.002c4.258 0 8.518-.004 12.776 0zM8.65 7.856c-1.262.135-1.99.57-2.357 1.476-.392.965-.115 1.81.606 2.496a746.818 746.818 0 0 0 7.398 6.966c1.086 1.003 2.237.99 3.314-.013a700.448 700.448 0 0 0 7.17-6.747c1.203-1.148 1.32-2.468.365-3.426-1.01-1.014-2.302-.933-3.558.245-1.596 1.497-3.222 2.965-4.75 4.526-.706.715-1.12.627-1.783-.034a123.71 123.71 0 0 0-4.93-4.644c-.47-.42-1.123-.647-1.478-.844z"/></svg>
				  </span>
				  <span class="rrssb-text">pocket</span>
				</a>
			  </li>

			  <li class="rrssb-youtube">
				<a href="#">
				  <span class="rrssb-icon">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28"><path d="M27.688 8.512a4.086 4.086 0 0 0-4.106-4.093H4.39A4.084 4.084 0 0 0 .312 8.51v10.976A4.08 4.08 0 0 0 4.39 23.58h19.19a4.09 4.09 0 0 0 4.107-4.092V8.512zm-16.425 10.12V8.322l7.817 5.154-7.817 5.156z"/></svg>
				  </span>
				  <span class="rrssb-text">youtube</span>
				</a>
			  </li>
			  */ ?>

			  <li class="rrssb-pinterest">
				<a href="#" rel="nofollow">
				  <span class="rrssb-icon">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28"><path d="M14.02 1.57c-7.06 0-12.784 5.723-12.784 12.785S6.96 27.14 14.02 27.14c7.062 0 12.786-5.725 12.786-12.785 0-7.06-5.724-12.785-12.785-12.785zm1.24 17.085c-1.16-.09-1.648-.666-2.558-1.22-.5 2.627-1.113 5.146-2.925 6.46-.56-3.972.822-6.952 1.462-10.117-1.094-1.84.13-5.545 2.437-4.632 2.837 1.123-2.458 6.842 1.1 7.557 3.71.744 5.226-6.44 2.924-8.775-3.324-3.374-9.677-.077-8.896 4.754.19 1.178 1.408 1.538.49 3.168-2.13-.472-2.764-2.15-2.683-4.388.132-3.662 3.292-6.227 6.46-6.582 4.008-.448 7.772 1.474 8.29 5.24.58 4.254-1.815 8.864-6.1 8.532v.003z"/></svg>
				  </span>
				  <span class="rrssb-text">pinterest</span>
				</a>
			  </li>

			  <?php /*
			  <li class="rrssb-github">
				<a href="#">
				  <span class="rrssb-icon">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28"><path d="M13.97 1.57c-7.03 0-12.733 5.703-12.733 12.74 0 5.622 3.636 10.393 8.717 12.084.637.13.87-.277.87-.615 0-.302-.013-1.103-.02-2.165-3.54.77-4.29-1.707-4.29-1.707-.578-1.473-1.413-1.863-1.413-1.863-1.154-.79.09-.775.09-.775 1.276.104 1.96 1.316 1.96 1.312 1.135 1.936 2.99 1.393 3.712 1.06.116-.823.445-1.384.81-1.704-2.83-.32-5.802-1.414-5.802-6.293 0-1.39.496-2.527 1.312-3.418-.132-.322-.57-1.617.123-3.37 0 0 1.07-.343 3.508 1.305A12.22 12.22 0 0 1 14 7.732c1.082 0 2.167.156 3.198.44 2.43-1.65 3.498-1.307 3.498-1.307.695 1.754.258 3.043.13 3.37a4.968 4.968 0 0 1 1.314 3.43c0 4.893-2.978 5.97-5.814 6.286.458.388.876 1.16.876 2.358 0 1.703-.016 3.076-.016 3.482 0 .334.232.748.877.61 5.056-1.687 8.7-6.456 8.7-12.08-.055-7.058-5.75-12.757-12.792-12.75z"/></svg>
				  </span>
				  <span class="rrssb-text">github</span>
				</a>
			  </li>

			  <li class="rrssb-print">
				<a href="javascript:window.print()">
				  <span class="rrssb-icon">
					<svg height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
				  </span>
				  <span class="rrssb-text">print</span>
				</a>
			  </li>

			  <li class="rrssb-whatsapp">
				<a href="#" data-action="share/whatsapp/share">
				  <span class="rrssb-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="90" height="90" viewBox="0 0 90 90"><path d="M90 43.84c0 24.214-19.78 43.842-44.182 43.842a44.256 44.256 0 0 1-21.357-5.455L0 90l7.975-23.522a43.38 43.38 0 0 1-6.34-22.637C1.635 19.63 21.415 0 45.818 0 70.223 0 90 19.628 90 43.84zM45.818 6.983c-20.484 0-37.146 16.535-37.146 36.86 0 8.064 2.63 15.533 7.076 21.61l-4.64 13.688 14.274-4.537A37.122 37.122 0 0 0 45.82 80.7c20.48 0 37.145-16.533 37.145-36.857S66.3 6.983 45.818 6.983zm22.31 46.956c-.272-.447-.993-.717-2.075-1.254-1.084-.537-6.41-3.138-7.4-3.495-.993-.36-1.717-.54-2.438.536-.72 1.076-2.797 3.495-3.43 4.212-.632.72-1.263.81-2.347.27-1.082-.536-4.57-1.672-8.708-5.332-3.22-2.848-5.393-6.364-6.025-7.44-.63-1.076-.066-1.657.475-2.192.488-.482 1.084-1.255 1.625-1.882.543-.628.723-1.075 1.082-1.793.363-.718.182-1.345-.09-1.884-.27-.537-2.438-5.825-3.34-7.977-.902-2.15-1.803-1.793-2.436-1.793-.63 0-1.353-.09-2.075-.09-.722 0-1.896.27-2.89 1.344-.99 1.077-3.788 3.677-3.788 8.964 0 5.288 3.88 10.397 4.422 11.113.54.716 7.49 11.92 18.5 16.223 11.01 4.3 11.01 2.866 12.996 2.686 1.984-.18 6.406-2.6 7.312-5.107.9-2.513.9-4.664.63-5.112z"/></svg>
				  </span>
				  <span class="rrssb-text">Whatsapp</span>
				</a>
			  </li>
			  */ ?>

		</ul>
	</div>
	<!-- Buttons end here -->
</div>

<?php $cs->registerScriptFile(Yii::app()->homeUrl . 'js/vendor/rrssb/js/rrssb.min.js', CClientScript::POS_END); ?>
<?php
  $params = [];
  if (isset($title)) $params['title'] = $title;
  if (isset($url)) $params['url'] = $url;
  if (isset($description)) $params['description'] = $description;
?>
<?php $cs->registerScript('rrssb', "
  $('.rrssb-buttons').rrssb(" . json_encode($params) . ");
", CClientScript::POS_END); ?>
