<?php
function hpm_site_header() { ?>
			<header id="masthead" class="site-header" role="banner">
				<div class="site-branding">
					<div class="site-logo">
						<a href="/" rel="home" title="<?php bloginfo( 'name' ); ?>"><span class="hidden">Houston Public Media, a Service of the University of Houston</span><svg id="head-logo" data-name="Houston Public Media, a service of the University of Houston" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 872.96 231.64" aria-hidden="true"><path class="cls-1" d="M.35,4.35H11.46V30.53H42.82V4.35H53.93V67.26H42.82V40H11.46V67.26H.35Z"/><path class="cls-1" d="M75.1,65.37A20.64,20.64,0,0,1,66.78,57a26.65,26.65,0,0,1-3-13,26.63,26.63,0,0,1,3-13.09,20.74,20.74,0,0,1,8.38-8.38,25.22,25.22,0,0,1,12.16-2.87A24.3,24.3,0,0,1,99.3,22.53a20.81,20.81,0,0,1,8.28,8.33,25.42,25.42,0,0,1,3,12.53,27.28,27.28,0,0,1-3.06,13.28,21.35,21.35,0,0,1-8.42,8.61,25.86,25.86,0,0,1-24,.09ZM96,55.1c2.31-2.69,3.46-6.52,3.46-11.52q0-7-3.33-10.92a11.1,11.1,0,0,0-8.88-3.88,11.52,11.52,0,0,0-8.93,3.83c-2.31,2.57-3.47,6.31-3.47,11.25S76,52.41,78.34,55.1A11.73,11.73,0,0,0,96,55.1Z"/><path class="cls-1" d="M159.66,20.63V67.26h-9L150,63.19a28.89,28.89,0,0,1-6.53,3.66,23,23,0,0,1-8.55,1.43q-7.5,0-11.57-4.25t-4.07-11.94V20.63h10.55V49.78q0,9.16,8.51,9.16a12.3,12.3,0,0,0,6.24-1.62,16.34,16.34,0,0,0,4.58-3.75V20.63Z"/><path class="cls-1" d="M169.21,65.51V55.79a25.11,25.11,0,0,0,6.38,2.59,28,28,0,0,0,7.5,1.11,13,13,0,0,0,6.48-1.38,4.17,4.17,0,0,0,2.4-3.7,4.76,4.76,0,0,0-1.9-4q-1.89-1.49-7.44-3.43-7.32-2.58-10.41-5.78a11.5,11.5,0,0,1-3.1-8.37,11.18,11.18,0,0,1,2.17-6.71,14.66,14.66,0,0,1,6.34-4.77,25.68,25.68,0,0,1,10-1.76,39.4,39.4,0,0,1,7.08.61A27.84,27.84,0,0,1,200,21.65v9.07a28.53,28.53,0,0,0-5.36-1.62,29.82,29.82,0,0,0-6.21-.69,15.12,15.12,0,0,0-6.52,1.15c-1.57.77-2.36,1.77-2.36,3a3.56,3.56,0,0,0,1.76,3.1,39,39,0,0,0,6.66,3l2.13.83q6.75,2.6,9.53,5.83t2.78,9a12.21,12.21,0,0,1-4.91,10.18q-4.91,3.8-14,3.79A35.21,35.21,0,0,1,169.21,65.51Z"/><path class="cls-1" d="M223.36,28.87V52.28q0,6.84,6.48,6.84a18.48,18.48,0,0,0,5.83-.92V67a17.49,17.49,0,0,1-3.75.92,30.87,30.87,0,0,1-4.86.37q-7.31,0-10.77-3.79t-3.47-10.27V28.87h-6.76V20.63h6.76V10.55L223.36,7.4V20.63H236v8.24Z"/><path class="cls-1" d="M251,65.37A20.66,20.66,0,0,1,242.69,57a26.76,26.76,0,0,1-3-13,26.53,26.53,0,0,1,3.05-13.09,20.72,20.72,0,0,1,8.37-8.38,25.26,25.26,0,0,1,12.17-2.87,24.33,24.33,0,0,1,11.94,2.92,20.94,20.94,0,0,1,8.28,8.33,25.41,25.41,0,0,1,3,12.53,27.28,27.28,0,0,1-3.05,13.28A21.42,21.42,0,0,1,275,65.28a25.86,25.86,0,0,1-24,.09ZM271.93,55.1c2.31-2.69,3.47-6.52,3.47-11.52q0-7-3.33-10.92a11.1,11.1,0,0,0-8.88-3.88,11.51,11.51,0,0,0-8.93,3.83c-2.32,2.57-3.47,6.31-3.47,11.25s1.15,8.55,3.47,11.24a11.72,11.72,0,0,0,17.67,0Z"/><path class="cls-1" d="M332,23.87q4.4,4.26,4.4,12V67.26H325.8V37.94a9.73,9.73,0,0,0-2.13-6.67q-2.13-2.4-6.48-2.4a12.61,12.61,0,0,0-6.2,1.48,23.47,23.47,0,0,0-5,3.7V67.26H295.45V20.63h9.07l.92,4.26A26.8,26.8,0,0,1,312,21a21.46,21.46,0,0,1,8.05-1.39Q327.56,19.61,332,23.87Z"/><path class="cls-1" d="M408.1,9.76q6.15,5.41,6.15,14.3a21.1,21.1,0,0,1-2.92,11.19,19.72,19.72,0,0,1-8.28,7.5,27.76,27.76,0,0,1-12.58,2.68h-6.2V67.26h-11.1V4.35h17.49Q401.94,4.35,408.1,9.76ZM399.4,33a11.42,11.42,0,0,0,3.47-8.55,10,10,0,0,0-3.47-8.1c-2.31-1.94-5.45-2.91-9.39-2.91h-5.74V36.36h5.18Q395.93,36.36,399.4,33Z"/><path class="cls-1" d="M461.72,20.63V67.26h-9L452,63.19a28.89,28.89,0,0,1-6.53,3.66,23,23,0,0,1-8.56,1.43q-7.49,0-11.56-4.25t-4.07-11.94V20.63h10.55V49.78q0,9.16,8.51,9.16a12.3,12.3,0,0,0,6.24-1.62,16.49,16.49,0,0,0,4.58-3.75V20.63Z"/><path class="cls-1" d="M506,22.48a19.57,19.57,0,0,1,7.54,8.15,26.65,26.65,0,0,1,2.68,12.21A26.9,26.9,0,0,1,513,56.16a22.5,22.5,0,0,1-9,8.93,27.37,27.37,0,0,1-13.46,3.19,48.76,48.76,0,0,1-8.7-.83,48.15,48.15,0,0,1-8.42-2.31V0H484V21.93a24.67,24.67,0,0,1,10.64-2.32A21.92,21.92,0,0,1,506,22.48Zm-4.77,32.34q3.88-4.3,3.89-11.33,0-6.57-3.19-10.55a10.43,10.43,0,0,0-8.56-4,19.09,19.09,0,0,0-9.35,2.5V57.92a18.43,18.43,0,0,0,6.94,1.2A13.16,13.16,0,0,0,501.22,54.82Z"/><path class="cls-1" d="M525.38,0h10.55V67.26H525.38Z"/><path class="cls-1" d="M548.52,12a5.77,5.77,0,0,1-1.9-4.44,5.87,5.87,0,0,1,1.85-4.44,6.47,6.47,0,0,1,4.63-1.76,6.65,6.65,0,0,1,4.62,1.76,5.71,5.71,0,0,1,1.95,4.44A5.71,5.71,0,0,1,557.72,12a6.65,6.65,0,0,1-4.62,1.76A6.5,6.5,0,0,1,548.52,12Zm-.7,8.6h10.55V67.26H547.82Z"/><path class="cls-1" d="M578.6,65.23a20.73,20.73,0,0,1-8.42-8.51A26.46,26.46,0,0,1,567.26,44a26.29,26.29,0,0,1,3-12.63,21.32,21.32,0,0,1,8.65-8.65,27.82,27.82,0,0,1,13.65-3.15,29,29,0,0,1,11.38,2v9.72a23.35,23.35,0,0,0-10.55-2.22q-7.12,0-11.05,4.11t-3.94,10.87q0,6.85,3.89,10.74a13.89,13.89,0,0,0,10.27,3.88,29.93,29.93,0,0,0,6.2-.64,20.24,20.24,0,0,0,5.18-1.76v9.71a31.51,31.51,0,0,1-12.3,2.22A26.44,26.44,0,0,1,578.6,65.23Z"/><path class="cls-1" d="M699.48,4.35V67.26H689V24.43L669.68,55.05h-2.77l-19.52-30.9V67.26H637V4.35h10l21.65,35.16L690.32,4.35Z"/><path class="cls-1" d="M752.44,46.82H720.61q1.47,12.49,14.62,12.49a29.53,29.53,0,0,0,14.89-3.7v9.53a29.47,29.47,0,0,1-7,2.27,42.66,42.66,0,0,1-8.65.87,28.27,28.27,0,0,1-13.37-3,21,21,0,0,1-8.75-8.52,26.23,26.23,0,0,1-3-12.9,27.24,27.24,0,0,1,2.87-12.73,20.8,20.8,0,0,1,8-8.51,23,23,0,0,1,11.8-3,21,21,0,0,1,11.15,2.92,19.06,19.06,0,0,1,7.26,8,25.12,25.12,0,0,1,2.5,11.33A20,20,0,0,1,752.44,46.82ZM724.82,31.27a13,13,0,0,0-3.93,7.59h21.83a11.79,11.79,0,0,0-3.14-7.31,9.69,9.69,0,0,0-7.41-3A10.62,10.62,0,0,0,724.82,31.27Z"/><path class="cls-1" d="M802.16,0V64.77a53.38,53.38,0,0,1-18.51,3.51q-11,0-17.62-6.29t-6.62-18a26.51,26.51,0,0,1,2.92-12.63,21.1,21.1,0,0,1,8.28-8.6A24.29,24.29,0,0,1,783,19.61a19.89,19.89,0,0,1,8.6,1.76V0ZM791.61,58V30.72a15.8,15.8,0,0,0-7.68-1.85,12.28,12.28,0,0,0-9.71,4.21q-3.71,4.2-3.7,11.15,0,7.13,3.74,11.05a12.74,12.74,0,0,0,9.67,3.94A22.11,22.11,0,0,0,791.61,58Z"/><path class="cls-1" d="M814.64,12a5.77,5.77,0,0,1-1.9-4.44,5.87,5.87,0,0,1,1.85-4.44,6.48,6.48,0,0,1,4.63-1.76,6.69,6.69,0,0,1,4.63,1.76,5.74,5.74,0,0,1,1.94,4.44A5.74,5.74,0,0,1,823.85,12a6.69,6.69,0,0,1-4.63,1.76A6.5,6.5,0,0,1,814.64,12Zm-.69,8.6h10.54V67.26H814Z"/><path class="cls-1" d="M868.1,24.38Q873,29.15,873,38V67.26h-8.42L863.24,63a21.94,21.94,0,0,1-14.43,5.27q-6.94,0-11.06-3.88t-4.11-10.55q0-8,5.82-11.57t15-3.6a35.94,35.94,0,0,1,8,.83v-.46q0-4.9-2-7.64t-8.15-2.73a31.65,31.65,0,0,0-7.45.93,35.16,35.16,0,0,0-7.26,2.59V22.58a44.73,44.73,0,0,1,16.1-3Q863.25,19.61,868.1,24.38ZM846.36,57.64a7.15,7.15,0,0,0,5,1.67,18.87,18.87,0,0,0,11-3.79V46.91a33.14,33.14,0,0,0-6.29-.56,19.27,19.27,0,0,0-8.19,1.58,5.63,5.63,0,0,0-3.38,5.55A5.4,5.4,0,0,0,846.36,57.64Z"/><path d="M15.4,124.21H6.34L4.48,129.5H0l9.2-24h3.38l9.24,24H17.31Zm-1.23-3.46-3.35-9.34-3.28,9.34Z"/><path d="M43.51,129.44a12.57,12.57,0,0,1-3-1v-4.19a13.19,13.19,0,0,0,3,1.34,11.55,11.55,0,0,0,3.44.56,5.64,5.64,0,0,0,3.4-.86A2.73,2.73,0,0,0,51.5,123a2.82,2.82,0,0,0-1.17-2.32,18.33,18.33,0,0,0-4.09-2.12,12.12,12.12,0,0,1-4.47-2.82,5.64,5.64,0,0,1-1.45-4,5.77,5.77,0,0,1,1.08-3.49,6.83,6.83,0,0,1,3-2.27,10.84,10.84,0,0,1,4.25-.79,18.33,18.33,0,0,1,3.24.24,20.64,20.64,0,0,1,2.57.67V110a16.69,16.69,0,0,0-2.52-.77,12.66,12.66,0,0,0-2.8-.32,6.07,6.07,0,0,0-3.15.71,2.28,2.28,0,0,0-1.19,2.08,2.19,2.19,0,0,0,.55,1.49,4.86,4.86,0,0,0,1.5,1.1c.63.31,1.69.79,3.17,1.42a11.59,11.59,0,0,1,4.44,3,6.15,6.15,0,0,1,1.38,4,6.87,6.87,0,0,1-1,3.68,6.66,6.66,0,0,1-2.94,2.52,11.08,11.08,0,0,1-4.71.92A16.47,16.47,0,0,1,43.51,129.44Z"/><path d="M78.2,126.08v3.42H64v-24H78.09V109h-9.9v6.76h9.23v3.42H68.19v7Z"/><path d="M99.48,129.5l-4.93-9.06c-.24,0-.59,0-1.06,0H91.13v9H86.9v-24h6.66a10.09,10.09,0,0,1,6.64,2,6.43,6.43,0,0,1,2.35,5.2,7.5,7.5,0,0,1-1.11,4.09,7.19,7.19,0,0,1-3.12,2.71l5.88,10Zm-2.55-13.63a4,4,0,0,0,1.28-3.05A3.45,3.45,0,0,0,96.93,110a5.71,5.71,0,0,0-3.62-1H91.13v8.1h2A5.49,5.49,0,0,0,96.93,115.87Z"/><path d="M130.28,105.53l-9.34,24h-3.45l-9.24-24h4.62l6.45,17.9,6.45-17.9Z"/><path d="M137.21,105.53h4.23v24h-4.23Z"/><path d="M155.7,128.32a10.81,10.81,0,0,1-4.3-4.31,12.83,12.83,0,0,1-1.54-6.32,13.56,13.56,0,0,1,1.54-6.54,10.8,10.8,0,0,1,4.4-4.41,13.9,13.9,0,0,1,6.72-1.56,18.1,18.1,0,0,1,5.78.88V110a14.41,14.41,0,0,0-5.43-1.09A9,9,0,0,0,158.3,110a7.39,7.39,0,0,0-2.94,3.1,10,10,0,0,0-1,4.6,9.34,9.34,0,0,0,1,4.39,7.33,7.33,0,0,0,2.82,3,8,8,0,0,0,4.18,1.08,14.49,14.49,0,0,0,5.92-1.27v3.91a13.74,13.74,0,0,1-2.78.79,19.52,19.52,0,0,1-3.46.27A12.83,12.83,0,0,1,155.7,128.32Z"/><path d="M190.91,126.08v3.42H176.67v-24H190.8V109h-9.9v6.76h9.23v3.42H180.9v7Z"/><path d="M217.18,128.3a10.86,10.86,0,0,1-4.19-4.36,13.31,13.31,0,0,1-1.5-6.39A13.67,13.67,0,0,1,213,111a10.5,10.5,0,0,1,4.21-4.32,12.66,12.66,0,0,1,6.28-1.51,12.51,12.51,0,0,1,6.16,1.49,10.65,10.65,0,0,1,4.25,4.27,13.14,13.14,0,0,1,1.53,6.47,13.69,13.69,0,0,1-1.49,6.52,10.73,10.73,0,0,1-4.23,4.37,12.8,12.8,0,0,1-6.4,1.55A12.1,12.1,0,0,1,217.18,128.3Zm11.74-4.43a9.26,9.26,0,0,0,2-6.39,10.58,10.58,0,0,0-.95-4.64,6.84,6.84,0,0,0-2.65-3,7.41,7.41,0,0,0-3.87-1,7.3,7.3,0,0,0-3.9,1.05,7.13,7.13,0,0,0-2.66,3,10.18,10.18,0,0,0-.95,4.51,10.78,10.78,0,0,0,.93,4.65,7,7,0,0,0,2.61,3,7.19,7.19,0,0,0,3.86,1A7,7,0,0,0,228.92,123.87Z"/><path d="M248,109v7.29h9v3.46h-9v9.73H243.8v-24h13.88V109Z"/><path d="M283.56,109H277v-3.45h17.34V109h-6.56V129.5h-4.22Z"/><path d="M301.67,105.53h4.23v10h12v-10h4.23v24h-4.23V119.1h-12v10.4h-4.23Z"/><path d="M346,126.08v3.42H331.78v-24h14.13V109H336v6.76h9.23v3.42H336v7Z"/><path d="M370.3,127.21q-2.54-2.65-2.54-7.9V105.53H372V119.2a7.94,7.94,0,0,0,1.48,5.15,5.17,5.17,0,0,0,4.23,1.8,5.08,5.08,0,0,0,4.2-1.84,8,8,0,0,0,1.48-5.14V105.53h4.3V119.1c0,3.55-.85,6.22-2.56,8s-4.17,2.72-7.42,2.72S372,129,370.3,127.21Z"/><path d="M418,105.53v24h-3.42L401,111.91V129.5h-3.8v-24h3.7l13.25,17.37V105.53Z"/><path d="M427.63,105.53h4.23v24h-4.23Z"/><path d="M460.8,105.53l-9.34,24H448l-9.24-24h4.62l6.45,17.9,6.45-17.9Z"/><path d="M482,126.08v3.42H467.73v-24h14.14V109H472v6.76h9.24v3.42H472v7Z"/><path d="M503.26,129.5l-4.94-9.06c-.23,0-.59,0-1.06,0H494.9v9h-4.23v-24h6.66a10.12,10.12,0,0,1,6.65,2,6.45,6.45,0,0,1,2.34,5.2,7.5,7.5,0,0,1-1.11,4.09,7.19,7.19,0,0,1-3.12,2.71l5.89,10Zm-2.56-13.63a3.93,3.93,0,0,0,1.29-3.05A3.43,3.43,0,0,0,500.7,110a5.68,5.68,0,0,0-3.61-1H494.9v8.1h2A5.46,5.46,0,0,0,500.7,115.87Z"/><path d="M517.53,129.44a12.5,12.5,0,0,1-3-1v-4.19a13.09,13.09,0,0,0,3,1.34,11.49,11.49,0,0,0,3.43.56,5.64,5.64,0,0,0,3.4-.86,2.7,2.7,0,0,0,1.15-2.28,2.82,2.82,0,0,0-1.17-2.32,18.2,18.2,0,0,0-4.08-2.12,12.17,12.17,0,0,1-4.48-2.82,5.68,5.68,0,0,1-1.44-4,5.76,5.76,0,0,1,1.07-3.49,6.89,6.89,0,0,1,3-2.27,10.84,10.84,0,0,1,4.25-.79,18.33,18.33,0,0,1,3.24.24,20.64,20.64,0,0,1,2.57.67V110a16.69,16.69,0,0,0-2.52-.77,12.59,12.59,0,0,0-2.8-.32,6.07,6.07,0,0,0-3.15.71,2.27,2.27,0,0,0-1.18,2.08,2.19,2.19,0,0,0,.54,1.49,5.12,5.12,0,0,0,1.5,1.1c.63.31,1.69.79,3.17,1.42a11.43,11.43,0,0,1,4.44,3,6.15,6.15,0,0,1,1.38,4,6.87,6.87,0,0,1-1,3.68,6.66,6.66,0,0,1-2.94,2.52,11.05,11.05,0,0,1-4.71.92A16.31,16.31,0,0,1,517.53,129.44Z"/><path d="M538,105.53h4.23v24H538Z"/><path d="M556.07,109h-6.55v-3.45h17.34V109H560.3V129.5h-4.23Z"/><path d="M593.67,105.53l-8.39,14.34v9.63H581v-9.56l-8.28-14.41h4.65l5.88,10.82,5.86-10.82Z"/><path d="M617.83,128.3a10.86,10.86,0,0,1-4.19-4.36,13.31,13.31,0,0,1-1.5-6.39,13.67,13.67,0,0,1,1.5-6.54,10.5,10.5,0,0,1,4.21-4.32,12.65,12.65,0,0,1,6.27-1.51,12.52,12.52,0,0,1,6.17,1.49,10.65,10.65,0,0,1,4.25,4.27,13.14,13.14,0,0,1,1.53,6.47,13.81,13.81,0,0,1-1.49,6.52,10.73,10.73,0,0,1-4.23,4.37,12.8,12.8,0,0,1-6.4,1.55A12.1,12.1,0,0,1,617.83,128.3Zm11.74-4.43a9.26,9.26,0,0,0,2-6.39,10.58,10.58,0,0,0-1-4.64,6.84,6.84,0,0,0-2.65-3,7.45,7.45,0,0,0-3.88-1,7.29,7.29,0,0,0-3.89,1.05,7.13,7.13,0,0,0-2.66,3,10.18,10.18,0,0,0-1,4.51,10.78,10.78,0,0,0,.93,4.65,6.92,6.92,0,0,0,2.61,3,7.19,7.19,0,0,0,3.86,1A7,7,0,0,0,629.57,123.87Z"/><path d="M648.67,109v7.29h9v3.46h-9v9.73h-4.22v-24h13.88V109Z"/><path d="M679.76,105.53H684v10h12v-10h4.23v24h-4.23V119.1H684v10.4h-4.23Z"/><path d="M714.26,128.3a10.82,10.82,0,0,1-4.2-4.36,13.31,13.31,0,0,1-1.5-6.39,13.67,13.67,0,0,1,1.5-6.54,10.5,10.5,0,0,1,4.21-4.32,13.67,13.67,0,0,1,12.45,0,10.69,10.69,0,0,1,4.24,4.27,13,13,0,0,1,1.54,6.47,13.7,13.7,0,0,1-1.5,6.52,10.73,10.73,0,0,1-4.23,4.37,12.77,12.77,0,0,1-6.4,1.55A12,12,0,0,1,714.26,128.3ZM726,123.87a9.26,9.26,0,0,0,2-6.39,10.58,10.58,0,0,0-.95-4.64,6.84,6.84,0,0,0-2.65-3,7.41,7.41,0,0,0-3.87-1,7.3,7.3,0,0,0-3.9,1.05,7.2,7.2,0,0,0-2.66,3,10.18,10.18,0,0,0-1,4.51,10.78,10.78,0,0,0,.93,4.65,7,7,0,0,0,2.61,3,7.19,7.19,0,0,0,3.86,1A7,7,0,0,0,726,123.87Z"/><path d="M743.18,127.21c-1.7-1.77-2.54-4.4-2.54-7.9V105.53h4.23V119.2a7.88,7.88,0,0,0,1.48,5.15,5.15,5.15,0,0,0,4.23,1.8,5.06,5.06,0,0,0,4.19-1.84,8,8,0,0,0,1.48-5.14V105.53h4.3V119.1c0,3.55-.85,6.22-2.55,8s-4.18,2.72-7.42,2.72S744.87,129,743.18,127.21Z"/><path d="M771.87,129.44a12.57,12.57,0,0,1-3-1v-4.19a13.19,13.19,0,0,0,3,1.34,11.6,11.6,0,0,0,3.44.56,5.64,5.64,0,0,0,3.4-.86,2.7,2.7,0,0,0,1.15-2.28,2.82,2.82,0,0,0-1.17-2.32,18.2,18.2,0,0,0-4.08-2.12,12.08,12.08,0,0,1-4.48-2.82,5.64,5.64,0,0,1-1.45-4,5.77,5.77,0,0,1,1.08-3.49,6.89,6.89,0,0,1,3-2.27,10.84,10.84,0,0,1,4.25-.79,18.33,18.33,0,0,1,3.24.24,20.64,20.64,0,0,1,2.57.67V110a16.69,16.69,0,0,0-2.52-.77,12.66,12.66,0,0,0-2.8-.32,6.07,6.07,0,0,0-3.15.71,2.28,2.28,0,0,0-1.19,2.08,2.19,2.19,0,0,0,.55,1.49,5,5,0,0,0,1.5,1.1c.63.31,1.69.79,3.17,1.42a11.43,11.43,0,0,1,4.44,3,6.15,6.15,0,0,1,1.38,4,6.87,6.87,0,0,1-1,3.68,6.66,6.66,0,0,1-2.94,2.52,11.08,11.08,0,0,1-4.71.92A16.47,16.47,0,0,1,771.87,129.44Z"/><path d="M796.55,109H790v-3.45h17.34V109h-6.55V129.5h-4.23Z"/><path d="M818.42,128.3a10.73,10.73,0,0,1-4.19-4.36,13.31,13.31,0,0,1-1.5-6.39,13.67,13.67,0,0,1,1.5-6.54,10.44,10.44,0,0,1,4.21-4.32,12.65,12.65,0,0,1,6.27-1.51,12.52,12.52,0,0,1,6.17,1.49,10.65,10.65,0,0,1,4.25,4.27,13.14,13.14,0,0,1,1.53,6.47,13.7,13.7,0,0,1-1.5,6.52,10.73,10.73,0,0,1-4.23,4.37,12.76,12.76,0,0,1-6.39,1.55A12.1,12.1,0,0,1,818.42,128.3Zm11.74-4.43a9.26,9.26,0,0,0,2-6.39,10.44,10.44,0,0,0-.95-4.64,6.76,6.76,0,0,0-2.64-3,7.45,7.45,0,0,0-3.88-1,7.27,7.27,0,0,0-3.89,1.05,7.13,7.13,0,0,0-2.66,3,10,10,0,0,0-1,4.51,10.77,10.77,0,0,0,.94,4.65,6.92,6.92,0,0,0,2.61,3,7.17,7.17,0,0,0,3.86,1A7,7,0,0,0,830.16,123.87Z"/><path d="M865.79,105.53v24h-3.42l-13.53-17.59V129.5H845v-24h3.7L862,122.9V105.53Z"/><polygon class="cls-2" points="505.03 224.43 505.03 175.7 455.22 175.7 455.22 224.43 505.03 224.43 505.03 224.43"/><polygon points="555.09 224.43 555.09 175.7 505.03 175.7 505.03 224.43 555.09 224.43 555.09 224.43"/><polygon class="cls-3" points="604.31 224.43 604.31 175.7 555.09 175.7 555.09 224.43 604.31 224.43 604.31 224.43"/><path class="cls-4" d="M485.35,213.27V198.5a7.38,7.38,0,0,0-1.26-4.77,5.09,5.09,0,0,0-4.11-1.5,7.2,7.2,0,0,0-5.15,2.58v18.46h-6V187.61h4.31l1.1,2.4c1.63-1.88,4-2.83,7.21-2.83a9.62,9.62,0,0,1,7.22,2.74c1.76,1.83,2.64,4.37,2.64,7.64v15.71Z"/><path class="cls-4" d="M529.59,213.78q5.86,0,9.25-3.4c2.27-2.27,3.39-5.5,3.39-9.7q0-13.5-12.26-13.5a7.72,7.72,0,0,0-5.54,2.16v-1.73h-6v32.48h6v-7.44a11.69,11.69,0,0,0,5.16,1.13Zm-1.34-21.48c2.76,0,4.73.62,5.93,1.85s1.78,3.36,1.78,6.39q0,4.26-1.8,6.22c-1.2,1.32-3.18,2-5.93,2a5.85,5.85,0,0,1-3.8-1.31V194a5.29,5.29,0,0,1,3.82-1.67Z"/><path class="cls-4" d="M586.73,193.24a6.32,6.32,0,0,0-3.49-1,4.73,4.73,0,0,0-3.68,1.88,6.82,6.82,0,0,0-1.61,4.61v14.55h-6V187.61h6v2.46a8.32,8.32,0,0,1,6.64-2.89,9.37,9.37,0,0,1,4.67.94l-2.53,5.12Z"/><path class="cls-5" d="M332.08,200.07a31.54,31.54,0,1,1-31.54-31.58,31.55,31.55,0,0,1,31.54,31.58"/><path class="cls-5" d="M411.22,196.55c-3.45-1.79-6.24-3.25-6.24-6,0-2,1.67-3.17,4.49-3.17a17,17,0,0,1,8.6,2.43v-7.13a23.23,23.23,0,0,0-8.6-1.89c-8.32,0-12.05,5-12.05,10.33,0,6.3,4.24,9.33,8.91,11.8s6.36,3.5,6.36,6.13c0,2.23-1.93,3.51-5.17,3.51a15.24,15.24,0,0,1-9.75-3.75v7.58a19.35,19.35,0,0,0,9.69,3c8.08,0,13.18-4.22,13.18-11,0-7-6-10-9.43-11.8"/><path class="cls-5" d="M387.49,198.61a8.85,8.85,0,0,0,3.75-7.79c0-6-4.4-9.7-11.46-9.7H368.22V219h12.07c9.25,0,13.46-5.95,13.46-11.47C393.75,203.17,391.37,199.79,387.49,198.61Zm-8.24-11.11a4.42,4.42,0,0,1,4.79,4.63c0,2.85-2,4.69-5.19,4.69h-3.17V187.5Zm-3.57,25.19v-9.9h4.71c3.76,0,6,1.84,6,4.92,0,3.3-2.25,5-6.69,5Z"/><path class="cls-5" d="M349.63,181.12h-10V219h7.45V207h1.5c9.32,0,15.11-5,15.11-13S358.45,181.12,349.63,181.12Zm-2.53,6.32h2.19c4.37,0,7.19,2.53,7.19,6.45,0,4.24-2.6,6.68-7.14,6.68H347.1Z"/><path class="cls-6" d="M323.51,200.37l-3.5.72v6.48a4,4,0,0,1-4.1,3.91h-1.79V219h-5.76v-7.53h1.79a4,4,0,0,0,4.1-3.91v-6.48l3.5-.72a1.16,1.16,0,0,0,.8-1.68l-9.18-17.57h5.76l9.18,17.57a1.16,1.16,0,0,1-.8,1.68m-12.6,0-3.5.72v6.48a4,4,0,0,1-4.1,3.91h-1.79V219H287.35v-9a13.89,13.89,0,0,1-10.09-13.11c-.21-8.65,7.13-15.73,15.77-15.73h9.5l9.18,17.57a1.16,1.16,0,0,1-.8,1.68m-7.54-6.29a3.61,3.61,0,1,0-3.61,3.61,3.61,3.61,0,0,0,3.61-3.61"/></svg></a>
					</div>
					<section>
						<div id="top-schedule">
							<div class="top-schedule-label"><button type="button" aria-expanded="false" aria-controls="top-schedule-link-wrap" ><span class="fas fa-calendar" aria-hidden="true"></span>Schedules</button></div>
							<div class="top-schedule-link-wrap" id="top-schedule-link-wrap">
								<div class="top-schedule-links"><a href="/tv8">TV 8 Guide</a></div>
								<div class="top-schedule-links"><a href="/news887">News 88.7</a></div>
								<div class="top-schedule-links"><a href="/classical">Classical</a></div>
								<div class="top-schedule-links"><a href="/mixtape">Mixtape</a></div>
							</div>
						</div>
						<div id="top-listen"><button data-href="/listen-live" data-dialog="480:855"><span class="fas fa-microphone" aria-hidden="true"></span>Listen</button></div>
						<div id="top-watch"><button data-href="/watch-live" data-dialog="820:850"><span class="fas fa-tv" aria-hidden="true"></span>Watch</button></div>
					</section>
					<div id="top-donate"><a href="/donate"><span class="fas fa-heart" aria-hidden="true"></span><br /><span class="top-mobile-text">Donate</span></a></div>
					<div tabindex="0" id="top-mobile-close" class="nav-button"><span class="fas fa-times" aria-hidden="true"></span><br /><span class="top-mobile-text">CLOSE</span></div>
					<nav id="site-navigation" class="main-navigation" role="navigation">
						<div tabindex="0" id="top-mobile-menu" class="nav-button" aria-expanded="false"><span class="fas fa-bars" aria-hidden="true"></span><br /><span class="top-mobile-text">MENU</span></div>
						<div class="nav-wrap">
							<div id="top-search" tabindex="0" aria-expanded="false"><span class="fas fa-search" aria-hidden="true"></span><?php get_search_form(); ?></div>
							<?php
								// Primary navigation menu.
								wp_nav_menu([
									'menu_class' => 'nav-menu',
									'theme_location' => 'head-main',
									'walker' => new HPMv2_Menu_Walker
								]);
							?>
						</div>
					</nav>
				</div>
			</header><?php
}

function hpm_header_info() {
	global $wp_query;
	$reqs = [
		'description' => 'Houston Public Media provides informative, thought-provoking and entertaining content through a multi-media platform that includes TV 8, News 88.7 and HPM Classical and reaches a combined weekly audience of more than 1.5 million.',
		'keywords' => [ 'Houston Public Media', 'KUHT', 'TV 8', 'Houston Public Media Schedule', 'Educational TV Programs', 'independent program broadcasts', 'University of Houston', 'nonprofit', 'NPR News', 'KUHF', 'Classical Music', 'Arts &amp; Culture', 'News 88.7' ],
		'permalink' => 'https://www.houstonpublicmedia.org',
		'title' => 'Houston Public Media',
		'thumb' => 'https://cdn.hpm.io/assets/images/HPM-logo-OGimage-2.jpg',
		'thumb_meta' => [
			'width' => 1200,
			'height' => 630,
			'mime-type' => 'image/jpeg'
		],
		'og_type' => 'website',
		'author' => [],
		'publish_date' => '',
		'modified_date' => '',
		'word_count' => 0,
		'npr_byline' => '',
		'npr_story_id' => '',
		'hpm_section' => '',
		'has_audio' => 0
	];

	if ( is_home() || is_404() ) :
		// Do Nothing
	else :
		$ID = $wp_query->queried_object_id;

		if ( is_author() ) :
			global $curauth;
			global $author_check;
			$reqs['og_type'] = 'profile';
			$reqs['permalink'] = get_author_posts_url( $curauth->ID, $curauth->user_nicename );
			$reqs['title'] = $curauth->display_name." | Houston Public Media";
			if ( !empty( $author_check ) ) :
				while ( $author_check->have_posts() ) :
					$author_check->the_post();
					$head_excerpt = htmlentities( wp_strip_all_tags( get_the_content(), true ), ENT_QUOTES );
					if ( !empty( $head_excerpt ) && $head_excerpt !== 'Biography pending.' ) :
						$reqs['description'] = $head_excerpt;
					endif;
					$author = get_post_meta( get_the_ID(), 'hpm_staff_meta', TRUE );
					$head_categories = get_the_terms( get_the_ID(), 'staff_category' );
					if ( !empty( $head_categories ) ) :
						$reqs['keywords'] = [];
						foreach( $head_categories as $hcat ) :
							$reqs['keywords'][] = $hcat->name;
						endforeach;
					endif;
					$reqs['title'] = $curauth->display_name.", ".$author['title']." | Houston Public Media";
				endwhile;
				wp_reset_query();
			endif;
		elseif ( is_archive() ) :
			if ( is_post_type_archive() ) :
				$obj = get_post_type_object( get_post_type() );
				$reqs['permalink'] = get_post_type_archive_link( get_post_type() );
				$reqs['title'] = $obj->labels->name . ' | Houston Public Media';
				$reqs['description'] = wp_strip_all_tags( $obj->description, true );
			else :
				$reqs['permalink'] = get_the_permalink( $ID );
				$reqs['title'] = $wp_query->queried_object->name . ' | Houston Public Media';
			endif;
		elseif ( is_single() || is_page() || get_post_type() == 'embeds' ) :
			$attach_id = get_post_thumbnail_id( $ID );
			if ( !empty( $attach_id ) ) :
				$feature_img = wp_get_attachment_image_src( $attach_id, 'large' );
				$reqs['thumb_meta'] = [
					'width' => $feature_img[1],
					'height' => $feature_img[2],
					'mime-type' => get_post_mime_type( $attach_id )
				];
				$reqs['thumb'] = $feature_img[0];
			endif;
			$reqs['title'] = wp_strip_all_tags( get_the_title( $ID ), true ) . ' | Houston Public Media';
			$reqs['permalink'] = get_the_permalink( $ID );
			$reqs['description'] = htmlentities( wp_strip_all_tags( get_excerpt_by_id( $ID ), true ), ENT_QUOTES );
			$reqs['og_type'] = 'article';
			$coauthors = get_coauthors( $ID );
			foreach ( $coauthors as $coa ) :
				$author_fb = '';
				if ( is_a( $coa, 'wp_user' ) ) :
					$author_check = new WP_Query( [
						'post_type' => 'staff',
						'post_status' => 'publish',
						'meta_query' => [ [
							'key' => 'hpm_staff_authid',
							'compare' => '=',
							'value' => $coa->ID
						] ]
					] );
					if ( $author_check->have_posts() ) :
						$author_meta = get_post_meta( $author_check->post->ID, 'hpm_staff_meta', true );
						if ( !empty( $author_meta['facebook'] ) ) :
							$author_fb = $author_meta['facebook'];
						endif;
					endif;
				elseif ( !empty( $coa->type ) && $coa->type == 'guest-author' ) :
					if ( !empty( $coa->linked_account ) ) :
						$authid = get_user_by( 'login', $coa->linked_account );
						$author_check = new WP_Query( [
							'post_type' => 'staff',
							'post_status' => 'publish',
							'meta_query' => [ [
								'key' => 'hpm_staff_authid',
								'compare' => '=',
								'value' => $authid->ID
							] ]
						] );
						if ( $author_check->have_posts() ) :
							$author_meta = get_post_meta( $author_check->post->ID, 'hpm_staff_meta', true );
							if ( !empty( $author_meta['facebook'] ) ) :
								$author_fb = $author_meta['facebook'];
							endif;
						endif;
					endif;
				endif;
				$reqs['author'][] = [
					'profile' => ( !empty( $author_fb ) ? $author_fb : get_author_posts_url( $coa->ID, $coa->user_nicename ) ),
					'first_name' => $coa->first_name,
					'last_name' => $coa->last_name,
					'username' => $coa->user_nicename
				];
			endforeach;
			$reqs['publish_date'] = get_the_date( 'c', $ID );
			$reqs['modified_date'] = get_the_modified_date( 'c', $ID );
			$reqs['description'] = htmlentities( wp_strip_all_tags( get_excerpt_by_id( $ID ), true ), ENT_QUOTES );
			$head_categories = get_the_category( $ID );
			$head_tags = wp_get_post_tags( $ID );
			$reqs['keywords'] = [];
			foreach( $head_categories as $hcat ) :
				$reqs['keywords'][] = $hcat->name;
			endforeach;
			foreach( $head_tags as $htag ) :
				$reqs['keywords'][] = $htag->name;
			endforeach;
			if ( get_post_type() === 'post' ) :
				$reqs['word_count'] = word_count( $ID );
				$reqs['has_audio'] = ( preg_match( '/\[audio/', $wp_query->post->post_content ) ? 1 : 0 );
				$npr_retrieved_story = get_post_meta( $ID, 'npr_retrieved_story', 1 );
				$reqs['npr_story_id'] = get_post_meta( $ID, 'npr_story_id', 1 );
				$reqs['hpm_section'] = hpm_top_cat( $ID );
				$reqs['npr_byline'] = ( $npr_retrieved_story == 1 ? get_post_meta( $ID, 'npr_byline', 1 ) : coauthors( ', ', ', ', '', '', false ) );
			elseif ( get_post_type() === 'staff' ) :
				$reqs['og_type'] = 'profile';
			endif;
		elseif ( is_page_template( 'page-npr-articles.php' ) ) :
			global $nprdata;
			$reqs['title'] = $nprdata['title'];
			$reqs['permalink'] = $nprdata['permalink'];
			$reqs['description'] = htmlentities( wp_strip_all_tags( $nprdata['excerpt'], true ), ENT_QUOTES );
			$reqs['keywords'] = $nprdata['keywords'];
			$reqs['thumb'] = $nprdata['image']['src'];
			$reqs['thumb_meta'] = [
				'width' => $nprdata['image']['width'],
				'height' => $nprdata['image']['height'],
				'mime-type' => $nprdata['image']['mime-type']
			];
			$reqs['publish_date'] = $nprdata['date'];
		endif;
	endif;
?>
		<script type='text/javascript'>var _sf_startpt=(new Date()).getTime();</script>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="description" content="<?PHP echo $reqs['description']; ?>" />
		<meta name="keywords" content="<?php echo implode( ', ', $reqs['keywords'] ); ?>" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="bitly-verification" content="7777946f1a0a"/>
		<meta name="google-site-verification" content="WX07OGEaNirk2km8RjRBernE0mA7_QL6ywgu6NXl1TM" />
		<meta name="theme-color" content="#f5f5f5">
		<link rel="icon" sizes="48x48" href="https://cdn.hpm.io/assets/images/favicon/icon-48.png">
		<link rel="icon" sizes="96x96" href="https://cdn.hpm.io/assets/images/favicon/icon-96.png">
		<link rel="icon" sizes="144x144" href="https://cdn.hpm.io/assets/images/favicon/icon-144.png">
		<link rel="icon" sizes="192x192" href="https://cdn.hpm.io/assets/images/favicon/icon-192.png">
		<link rel="icon" sizes="256x256" href="https://cdn.hpm.io/assets/images/favicon/icon-256.png">
		<link rel="icon" sizes="384x384" href="https://cdn.hpm.io/assets/images/favicon/icon-384.png">
		<link rel="icon" sizes="512x512" href="https://cdn.hpm.io/assets/images/favicon/icon-512.png">
		<link rel="apple-touch-icon" sizes="57x57" href="https://cdn.hpm.io/assets/images/favicon/apple-touch-icon-57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="https://cdn.hpm.io/assets/images/favicon/apple-touch-icon-60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="https://cdn.hpm.io/assets/images/favicon/apple-touch-icon-72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="https://cdn.hpm.io/assets/images/favicon/apple-touch-icon-76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="https://cdn.hpm.io/assets/images/favicon/apple-touch-icon-114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="https://cdn.hpm.io/assets/images/favicon/apple-touch-icon-120.png">
		<link rel="apple-touch-icon" sizes="152x152" href="https://cdn.hpm.io/assets/images/favicon/apple-touch-icon-152.png">
		<link rel="apple-touch-icon" sizes="167x167" href="https://cdn.hpm.io/assets/images/favicon/apple-touch-icon-167.png">
		<link rel="apple-touch-icon" sizes="180x180" href="https://cdn.hpm.io/assets/images/favicon/apple-touch-icon-180.png">
		<link rel="mask-icon" href="https://cdn.hpm.io/assets/images/favicon/safari-pinned-tab.svg" color="#ff0000">
		<meta name="msapplication-config" content="https://cdn.hpm.io/assets/images/favicon/config.xml" />
		<link rel="manifest" href="/manifest.webmanifest">
		<meta name="apple-itunes-app" content="app-id=1549226694,app-argument=<?php echo $reqs['permalink']; ?>" />
		<meta name="google-play-app" content="app-id=com.jacobsmedia.KUHFV3" />
		<meta property="fb:app_id" content="523938487799321" />
		<meta property="fb:admins" content="37511993" />
		<meta property="fb:pages" content="27589213702" />
		<meta property="fb:pages" content="183418875085596" />
		<meta property="og:type" content="<?php echo $reqs['og_type'] ?>" />
		<meta property="og:title" content="<?php echo $reqs['title']; ?>" />
		<meta property="og:url" content="<?php echo $reqs['permalink']; ?>"/>
		<meta property="og:site_name" content="Houston Public Media" />
		<meta property="og:description" content="<?php echo $reqs['description']; ?>" />
		<meta property="og:image" content="<?php echo $reqs['thumb']; ?>" />
		<meta property="og:image:url" content="<?php echo $reqs['thumb']; ?>" />
		<meta property="og:image:height" content="<?php echo $reqs['thumb_meta']['height']; ?>" />
		<meta property="og:image:width" content="<?php echo $reqs['thumb_meta']['width']; ?>" />
		<meta property="og:image:type" content="<?php echo $reqs['thumb_meta']['mime-type']; ?>" />
		<meta property="og:image:secure_url" content="<?php echo $reqs['thumb']; ?>" />
		<script>var timeOuts = [];</script>
<?php
	if ( ( is_single() || is_page_template( 'page-npr-articles.php' ) ) && get_post_type() !== 'staff' && get_post_type() !== 'embeds' ) : ?>
		<meta property="article:content_tier" content="free" />
		<meta property="article:published_time" content="<?php echo $reqs['publish_date']; ?>" />
		<meta property="article:modified_time" content="<?php echo $reqs['modified_date']; ?>" />
		<meta property="article:publisher" content="https://www.facebook.com/houstonpublicmedia/" />
		<meta property="article:section" content="<?php echo $reqs['hpm_section']; ?>" />
<?php
		if ( !empty( $reqs['keywords'] ) ) :
			foreach( $reqs['keywords'] as $keys ) : ?>
		<meta property="article:tag" content="<?php echo $keys; ?>" />
<?php
			endforeach;
		endif;
		foreach ( $reqs['author'] as $aup ) : ?>
		<meta property="article:author" content="<?php echo $aup['profile']; ?>" />
<?php
		endforeach;
	endif;
	if ( is_author() || ( is_single() && get_post_type() === 'staff' ) ) : ?>
		<meta property="profile:first_name" content="<?php echo $curauth->first_name; ?>">
		<meta property="profile:last_name" content="<?php echo $curauth->last_name; ?>">
		<meta property="profile:username" content="<?php echo $curauth->user_nicename; ?>">
<?php
	endif; ?>
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:site" content="@houstonpubmedia" />
		<meta name="twitter:creator" content="@houstonpubmedia" />
		<meta name="twitter:title" content="<?php echo $reqs['title']; ?>" />
		<meta name="twitter:image" content="<?php echo $reqs['thumb']; ?>" />
		<meta name="twitter:url" content="<?php echo $reqs['permalink']; ?>" />
		<meta name="twitter:description" content="<?php echo $reqs['description']; ?>">
		<meta name="twitter:widgets:link-color" content="#000000">
		<meta name="twitter:widgets:border-color" content="#000000">
		<meta name="twitter:partner" content="tfwp">
<?php
	if ( is_single() && get_post_type() !== 'staff' && get_post_type() !== 'embeds' ) : ?>
		<meta name="datePublished" content="<?php echo $reqs['publish_date']; ?>" />
		<meta name="story_id" content="<?php echo $reqs['npr_story_id']; ?>" />
		<meta name="has_audio" content="<?php echo $reqs['has_audio']; ?>" />
		<meta name="programs" content="none" />
		<meta name="category" content="<?php echo $reqs['hpm_section']; ?>" />
		<meta name="org_id" content="220" />
		<meta name="author" content="<?php echo $reqs['npr_byline']; ?>" />
		<meta name="wordCount" content="<?php echo $reqs['word_count']; ?>" />
<?php
	endif;
}
add_action( 'wp_head', 'hpm_header_info', 1 );
add_action( 'wp_head', 'hpm_google_tracker', 100 );

function hpm_body_open() {
	global $wp_query;
	if ( !empty( $_GET['browser'] ) && $_GET['browser'] == 'inapp' ) : ?>
	<script>setCookie('inapp','true',1);</script>
	<style>#foot-banner, #top-donate, #masthead nav#site-navigation .nav-top.nav-donate, .top-banner { display: none; }</style>
<?php endif; ?>
		<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'hpmv2' ); ?></a>
<?php
	if ( !is_page_template( 'page-listen.php' ) && !is_page_template( 'page-blank.php' ) ) : ?>
		<div class="container">
			<?php hpm_site_header(); ?>
		</div>
		<?php echo hpm_talkshows(); ?>
<?php
	elseif ( is_page_template( 'page-listen.php' ) ) : ?>
		<div class="container">
			<header id="masthead" class="site-header" role="banner">
				<div class="site-branding">
					<div class="site-logo">
						<a href="/" rel="home" title="<?php bloginfo( 'name' ); ?>">&nbsp;</a>
					</div>
					<div id="top-donate"><a href="/donate"><span class="fas fa-heart" aria-hidden="true"></span><br /><span class="top-mobile-text">Donate</span></a></div>
					<div id="top-mobile-menu"><span class="fas fa-bars" aria-hidden="true"></span><br /><span class="top-mobile-text">Menu</span></div>
					<nav id="site-navigation" class="main-navigation" role="navigation">
						<div id="top-search"><span class="fas fa-search" aria-hidden="true"></span><?php get_search_form(); ?></div>
						<?php
							wp_nav_menu( array(
								'menu_class' => 'nav-menu',
								'menu' => 12244,
								'walker' => new HPMv2_Menu_Walker
							) ); ?>
						<div class="clear"></div>
					</nav>
				</div>
			</header>
		</div>
<?php
	endif; ?>
		<div id="page" class="hfeed site">
			<div id="content" class="site-content">
<?php
	if ( !is_page_template( 'page-listen.php' ) && !is_page_template( 'page-blank.php' ) ) : ?>
			<!-- /9147267/HPM_Under_Nav -->
				<div id='div-gpt-ad-1488818411584-0'>
					<script>
						googletag.cmd.push(function() { googletag.display('div-gpt-ad-1488818411584-0'); });
					</script>
				</div>
<?php
	endif;
}
add_action( 'body_open', 'hpm_body_open' );

function hpm_talkshows() {
	wp_reset_query();
	global $wp_query;
	$t = time();
	$offset = get_option('gmt_offset')*3600;
	$t = $t + $offset;
	$now = getdate($t);
	$output = '';
	$anc = get_post_ancestors( get_the_ID() );
	$bans = [ 135762, 290722, 303436, 303018, 315974 ];
	$hm_air = hpm_houston_matters_check();
	if ( !in_array( 135762, $anc ) && !in_array( get_the_ID(), $bans ) && $wp_query->post->post_type !== 'embeds' ) :
		if ( ( $now['wday'] > 0 && $now['wday'] < 6 ) && ( $now['hours'] == 9 || $now['hours'] == 15 ) && $hm_air[ $now['hours'] ] ) :
			if ( $now['hours'] == 15 ) :
				$output .= '<div id="hm-top" class="townsquare"><p><span><a href="/listen-live/"><strong>Town Square</strong> is on the air now!</a> Join the conversation:</span> Call <strong>888.486.9677</strong> | Email <a href="mailto:talk@townsquaretalk.org">talk@townsquaretalk.org</a> | <a href="/listen-live/">Listen Live</a></p></div>';
			else :
				$output .= '<div id="hm-top"><p><span><a href="/listen-live/"><strong>Houston Matters</strong> is on the air now!</a> Join the conversation:</span> Call <strong>713.440.8870</strong> | Email <a href="mailto:talk@houstonmatters.org">talk@houstonmatters.org</a> | <a href="/listen-live/">Listen Live</a></p></div>';
			endif;
		endif;
	endif;
	return $output;
}