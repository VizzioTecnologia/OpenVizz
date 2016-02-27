<br />
<br />
<br />
<br />
<br />

<?php
	
	//------------------------------------------------------
	// Botões anchor
	
	echo vui_el_button(
		
		array(
			
			'url' => '#',
			'text' => 'Botão anchor somente com ícone',
			'icon' => 'config',
			'only_icon' => TRUE,
			
		)
		
	);
	
	echo vui_el_button(
		
		array(
			
			'url' => '#',
			'text' => 'Botão anchor com ícone e texto',
			'icon' => 'config',
			'only_icon' => FALSE,
			
		)
		
	);
	
	echo vui_el_button(
		
		array(
			
			'url' => '#',
			'text' => 'Botão anchor somente com texto',
			
		)
		
	);
	
	//------------------------------------------------------
	// Botões button
	
	echo vui_el_button(
		
		array(
			
			'url' => '#',
			'button_type' => 'button',
			'text' => 'Botão button somente com ícone',
			'icon' => 'config',
			'only_icon' => TRUE,
			
		)
		
	);
	
	echo vui_el_button(
		
		array(
			
			'url' => '#',
			'button_type' => 'button',
			'text' => 'Botão button com ícone e texto',
			'icon' => 'config',
			'only_icon' => FALSE,
			
		)
		
	);
	
	echo vui_el_button(
		
		array(
			
			'url' => '#',
			'button_type' => 'button',
			'text' => 'Botão button sem ícone',
			
		)
		
	);
	
	echo vui_el_button(
		
		array(
			
			'url' => '#',
			'button_type' => 'submit',
			'text' => 'Botão submit com ícone e texto',
			'icon' => 'config',
			'only_icon' => FALSE,
			
		)
		
	);
	
	echo vui_el_button(
		
		array(
			
			'url' => '#',
			'button_type' => 'submit',
			'text' => 'Botão submit sem ícone',
			
		)
		
	);
	
	//------------------------------------------------------
	// Input text
	
	echo vui_el_input_text(
		
		array(
			
			'text' => 'Caixa de texto sem ícone',
			
		)
		
	);
	
	echo vui_el_input_text(
		
		array(
			
			'text' => 'Caixa de texto com ícone',
			'icon' => 'config',
			
		)
		
	);
	
	//------------------------------------------------------
	// Combobox
	
	echo vui_el_dropdown(
		
		array(
			
			'text' => 'Select com ícone',
			'icon' => 'config',
			'options' => array(
				
				'option 1',
				'option 2',
				'option 3',
				'option 4',
				'option 5 loooooongo longo',
				
			)
			
		)
		
	);
	
	echo vui_el_dropdown(
		
		array(
			
			'text' => 'Select sem ícone',
			'options' => array(
				
				'option 1',
				'option 2',
				'option 3',
				'option 4',
				'option 5 loooooongo longo',
				
			)
			
		)
		
	);
	
	//------------------------------------------------------
	// checkbox
	
	echo vui_el_checkbox(
		
		array(
			
			'text' => 'Checkbox com ícone',
			'icon' => 'config',
			
		)
		
	);
	
	echo vui_el_checkbox(
		
		array(
			
			'text' => 'Checkbox sem ícone',
			
		)
		
	);
	
	echo vui_el_checkbox(
		
		array(
			
			'title' => 'Checkbox sem ícone e texto',
			
		)
		
	);
	
	echo vui_el_checkbox(
		
		array(
			
			'text' => 'Checkbox somente com ícone',
			'icon' => 'config',
			'only_icon' => TRUE,
			
		)
		
	);
	
	echo vui_el_checkbox(
		
		array(
			
			'text' => 'Checkbox com texto, e ícone como check',
			'icon' => 'config',
			'icon_as_check' => TRUE,
			
		)
		
	);
	
	echo vui_el_checkbox(
		
		array(
			
			'text' => 'Checkbox sem texto, e ícone como check',
			'icon' => 'config',
			'icon_as_check' => TRUE,
			'only_icon' => TRUE,
			
		)
		
	);
	
?>