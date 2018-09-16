import React from 'react'
import { connect } from 'react-redux'
import ImportModal from '../components/import/importModal'
import Timeline from '../components/timeline'
import Header from '../components/header'

import '../../sass/common/common.scss'
import '../../sass/page/home.scss'

class App extends React.Component {
    render() {
        return (
            <div>
                <ImportModal/>
                <Header/>
                <Timeline/>
            </div>
        )
    }
}

export default connect()(App)
