import autobind from 'autobind-decorator'
import React from 'react'
import { Button } from 'react-bootstrap'

@autobind
export default class ImportButton extends React.Component {
    
    constructor(props, context) {
        super(props, context)
        this.state = {
            isLoading: false
        }
    }
    
    handleClick() {
        this.setState({isLoading: true})
        this.props.import()
    }
    
    render() {
        let isLoading = this.state.isLoading
        return (
            <Button
                bsStyle="primary"
                disabled={isLoading}
                onClick={!isLoading ? this.handleClick : null}>
                {isLoading ? 'Loading...' : 'Import'}
            </Button>
        )
    }
}
