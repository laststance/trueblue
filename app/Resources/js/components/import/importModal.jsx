import autobind from 'autobind-decorator'
import React from 'react'
import { Modal } from 'react-bootstrap'
import { Button } from 'react-bootstrap'
import ImportButton from './importButton'

@autobind
export default class ImportModal extends React.Component {
    
    constructor(props, context) {
        super(props, context)
        this.state = {
            showModal: this.props.isShowImportModal
        }
    }
    
    componentWillReceiveProps(props) {
        this.setState({showModal: props.isShowImportModal})
    }
    
    render() {
        return (
            <Modal show={this.state.showModal} onHide={this.close} backdrop={'static'}>
                <Modal.Header>
                    <Modal.Title>Import</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    Import Recently 2 weeks tweet.
                </Modal.Body>
                <Modal.Footer>
                    <ImportButton
                        import={this.props.import}
                        isInitialImportDebug={this.props.isInitialImportDebug}
                    />
                </Modal.Footer>
            </Modal>
        )
    }
}
