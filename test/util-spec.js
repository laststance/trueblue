import assert from 'assert'
import { getKaomoji } from '../app/Resources/js/utils/util.js'

describe('test util', () => {
    it('getKaomoji', () => {
        assert(typeof getKaomoji() == 'string')
    })
})
