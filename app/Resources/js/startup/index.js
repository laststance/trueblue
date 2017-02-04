import React from 'react'
import { Provider } from 'react-redux'
import App from '../containers/index'
import ReactOnRails from 'react-on-rails'

const mainNode = () => {
    const store = ReactOnRails.getStore('indexStore')

    const reactComponent = (
        <Provider store={store}>
            <App/>
        </Provider>
    )
    return reactComponent
}

export default mainNode
