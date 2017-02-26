import autobind from 'autobind-decorator'
import React from 'react'
import { connect } from 'react-redux'
import Actions from '../../actions/home'
import { Button } from 'react-bootstrap'

@autobind
class ImportButton extends React.Component {
    
    constructor(props, context) {
        super(props, context)
        this.state = {
            isLoading: false
        }
    }
    
    handleClick() {
        this.setState({isLoading: true})
        
        if (this.props.isInitialImportDebug) {
            return setTimeout(function() {
                console.log('debug initial import')
                this.props.debugImport()
            }.bind(this), 8000)
        }
        
        this.props.import()
    }
    
    renderLoadingText() {
        if (this.state.isLoading) {
            return (
                <div><i className="fa fa-spinner fa-spin"></i> Loading...</div>
            )
        }
        
        return (
            <div>Import</div>
        )
    }
    
    render() {
        let isLoading = this.state.isLoading
        const loadingText = this.renderLoadingText()
        return (
            <Button
                bsStyle="primary"
                disabled={isLoading}
                onClick={!isLoading ? this.handleClick : null}
            >
                {loadingText}
            </Button>
        )
    }
}

const mapStateToProps = (state) => (
    {}
)

function mapDispatchToProps(dispatch) {
    return {
        debugImport: function () {
            dispatch(Actions.debugImport())
        }
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(ImportButton)
