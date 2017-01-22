import autobind from 'autobind-decorator'
import React from 'react'
import { Navbar } from 'react-bootstrap'
import { Nav } from 'react-bootstrap'
import Menu from './menu.jsx'
import { getKaomoji } from '../utils/util'

@autobind
export default class Header extends React.Component {
    render() {
        return (
            <Navbar className="root-header">
                <Nav>
                    <div className="pull-left header-title">
                        <div>Hello @{this.props.appUsername} <span
                            className="header-title-kaomoji">{getKaomoji()}</span></div>
                    </div>
                    <div className="pull-right">
                        <Menu onClick={this.props.getDailyJson} timelineDateList={this.props.timelineDateList}/>
                    </div>
                </Nav>
            </Navbar>
        )
    }
}
