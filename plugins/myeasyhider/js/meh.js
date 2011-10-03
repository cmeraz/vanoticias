/**
 * Package main JavaScript
 *
 * @package myeasyhider
 * @author Ugo Grandolini
 * @version 1.0.0
 */
//alert('meh');

function __meh_select(what) {

	if(what=='common')
	{
		var it='menu-links,menu-appearance,menu-plugins,menu-users,menu-tools,menu-settings,update-nag,screen-options-link-wrap,contextual-help-link,dashboard_primary,dashboard_incoming_links,dashboard_plugins,dashboard_secondary,wp-version-message,footer-upgrade';
	}
	else
	{
		var it='header-logo,favorite-actions,menu-posts,menu-media,menu-links,menu-pages,menu-comments,menu-appearance,menu-plugins,menu-users,menu-tools,menu-settings,update-nag,screen-options-link-wrap,contextual-help-link,dashboard_right_now,dashboard_quick_press,dashboard_recent_comments,dashboard_recent_drafts,dashboard_primary,dashboard_incoming_links,dashboard_plugins,dashboard_secondary,wp-version-message,footer-left,footer-upgrade';
	}
	var items = it.split(',');
	var el, elv;

	switch(what) {

		case 'all':
			for(i=0;i<items.length;i++)
			{
				el=document.getElementById('high-'+items[i]);
				elv=document.getElementById('val-'+items[i]);

				if(el && elv)
				{
					el.className='meh-selector-selected';
					elv.value=items[i];
				}
			}
			break;
			//
		case 'none':
			for(i=0;i<items.length;i++)
			{
				el=document.getElementById('high-'+items[i]);
				elv=document.getElementById('val-'+items[i]);

				if(el && elv)
				{
					el.className='meh-selector';
					elv.value='';
				}
			}
			break;
			//
		case 'common':
			__meh_select('none');
			for(i=0;i<items.length;i++)
			{
				el=document.getElementById('high-'+items[i]);
				elv=document.getElementById('val-'+items[i]);

				if(el && elv)
				{
					el.className='meh-selector-selected';
					elv.value=items[i];
				}
			}
			break;
			//
	}
}

function __meh_check(IDS) {

	var el='';
	id = IDS.split(',');

	for(i=0;i<id.length;i++)
	{
		el = document.getElementById(id[i]);
		if(el)
		{
			el.style.display='none';
		}
	}
	return;
}

function __meh_selector_toggler(id) {

	var el=document.getElementById('high-'+id);
	var elv=document.getElementById('val-'+id);

	if(el.className=='meh-selector')
	{
		el.className='meh-selector-selected';
		elv.value=id;
	}
	else
	{
		el.className='meh-selector';
		elv.value='';
	}
}
