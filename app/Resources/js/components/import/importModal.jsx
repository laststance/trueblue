import autobind from 'autobind-decorator'
import React from 'react'
import { connect } from 'react-redux'
import { Modal } from 'react-bootstrap'
import { Button } from 'react-bootstrap'
import ImportButton from './importButton'

@autobind
class ImportModal extends React.Component {
    
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
                    <Modal.Title>{this.props.transText.import.title}</Modal.Title>
                </Modal.Header>
                <Modal.Body style={{wordBreak: 'keep-all'}}>
                    {this.props.transText.import.desc}
                </Modal.Body>
                <Modal.Footer>
                    <ImportButton/>
                </Modal.Footer>
            </Modal>
        )
    }
}

const mapStateToProps = (state) => (
    {
        transText: state.homeState.transText,
        isShowImportModal: state.homeState.isShowImportModal
    }
)

export default connect(mapStateToProps)(ImportModal)
