import React from 'react';
import Settings from '../../src/settings/setting.js'
function settings ()
{
    return (
        getElementByID( 'srpSettings' ).append( <Settings></Settings> )
    )
}