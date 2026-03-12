import './admin.scss';
import React, { useState, useEffect } from 'react';
import domReady from '@wordpress/dom-ready';
import { createRoot } from '@wordpress/element';
import {  Panel, PanelBody, TabPanel,PanelRow, Button, Spinner ,Modal} from '@wordpress/components';
import FormCustomizerTab from './admin/FormCustomizerTab';
import DocumentationTab from './admin/DocumentationTab';




const AdminApp = () => {
    const [isPopupOpen, setIsPopupOpen] = useState(false); // State for popup visibility
    const [popupMessage, setPopupMessage] = useState(''); // State for popup message

    const openPopup = (message) => {
        setPopupMessage(message);
        setIsPopupOpen(true);
    };

    const closePopup = () => {
        setIsPopupOpen(false);
        setPopupMessage('');
    };
   const tabs = [
        {
            name: 'FormCustomizerTab',
            title: 'Form Customizer',
            content: <FormCustomizerTab  openPopup={openPopup}/>,
        },
        {
            name: 'DocumentationTab',
            title: 'Documentation',
            content: <DocumentationTab />,
        },
    ];

    return (
        <>
        <Panel>
            <TabPanel
                className=""
                activeClass="active-tab"
                tabs={tabs}
            >
                {(tab, selected) => (
                    <div
                        className={`tab-content m-10 ${
                            tab.name === selected ? 'active-tab' : ''
                        }`}
                    >
                        {tab.content}
                    </div>
                )}
            </TabPanel>
        </Panel>
        {isPopupOpen && (
                <Modal title={popupMessage} onRequestClose={closePopup}>
                </Modal>
            )}
        </>
    );
};


domReady(() => {
    const root = createRoot(
        document.getElementById('CFWJM-admin-root')
    );
    root.render(<AdminApp />);
});