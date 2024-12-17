<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

</head>
<body class="antialiased">
<div>
    <label for="link">Write Your Link:</label>
    <input name="link" type="text" id="link">
    <label>Select Your Quality:</label>
    <input type="checkbox" id="144p" name="quality" value="144p"><label for="144p">144p</label>
    <input type="checkbox" id="360p" name="quality" value="360p"><label for="360p">360p</label>
    <input type="checkbox" id="720p" name="quality" value="720p"><label for="720p">720p</label>
    <button type="button" onclick="submit()">Submit</button>
    <br>
    <h1 id="title">Name:</h1>
    <table>

        <tbody>
        <tr id="quality">
        </tr>
        </tbody>
    </table>
</div>
<script>
    let submit = () => {
        let link = document.getElementById('link').value
        let form = 'link=' + link
        let quality = document.getElementsByName('quality')
        for (let x = 0; x < quality.length; x++) {
            console.log([quality[x].checked, quality[x]])
            if (quality[x].checked) {
                form = form + '&quality[]=' + quality[x].value
            }
        }
        console.log(form)
        let xml = new XMLHttpRequest()
        xml.onload = function () {
            let response = JSON.parse(this.responseText)
            document.getElementById('title').innerHTML = response.data.video.name
            response.data.quality.map(value => {

                let element = document.createElement('td')
                let txt = document.createTextNode(value.quality)
                element.appendChild(txt);

                let linkA = document.createElement('a')
                linkA.href = 'file:///' + value.link_download
                linkA.download = 'download'
                let element2 = document.createElement('td')
                let txt2 = document.createTextNode(value.link_download)
                element2.appendChild(linkA)
                linkA.appendChild(txt2);
                const tb = document.getElementById("quality");
                tr = document.createElement('tr')
                tb.appendChild(tr)
                tr.appendChild(element);
                tr.appendChild(element2);
            })
            console.log(response)
        }
        xml.open("POST", "/api/video/download");
        xml.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xml.send(form);


    }
</script>
</body>
</html>
